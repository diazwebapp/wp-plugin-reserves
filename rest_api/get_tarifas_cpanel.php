<?php
add_action("rest_api_init",function(){

    register_rest_route('wp-reserves-system/v1','/inmuebles',
    array(
        'methods'=>'GET',
        'callback'=>'get_inmuebles_end_point_handler'
    ));
});

function get_inmuebles_end_point_handler(){
    global $wpdb; 
    
    //parametros de la api rest
    $params = [
        'persons' => isset($_GET['persons']) ? $_GET['persons'] : null, // numero minimo de huespedes
        'limit' => isset($_GET['limit']) ? $_GET['limit'] : 10
    ];

    // Pedimos todos los inmuebles
    $query_tarifas = $wpdb->get_results("SELECT * from {$wpdb->prefix}posts WHERE post_type='tarifas' ORDER BY ID DESC LIMIT 99999999999"); 
    $count = 0;
    $tarifas=[]; 
    // Le asignamos los meta
    foreach($query_tarifas as $key => $tarifa):
        $tarifa->meta = get_post_custom($tarifa->ID);
        $query_tarifas[$key] = $tarifa;
        if($query_tarifas[$key]->post_status != 'draft' && $tarifa->post_status != 'trash'){
            $count++ ;
        }
        if(isset($params['limit']) && $key < intval($params['limit']) && $query_tarifas[$key]->post_status != 'draft' && $tarifa->post_status != 'trash'){
                $tarifas[$key] = $query_tarifas[$key];
        }
    endforeach;
    

    $response = json_encode(
            [
                'tarifas'=>isset($params['limit']) ? $tarifas : $query_tarifas,
                'count'=>$count
            ]
        );
    // devolvemos los datos en decodificando el json
    return json_decode($response);
}