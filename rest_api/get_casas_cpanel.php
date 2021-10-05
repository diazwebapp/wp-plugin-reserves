<?php
add_action("rest_api_init",function(){

    register_rest_route('wp-reserves-system/v1','/casas',
    array(
        'methods'=>'GET',
        'callback'=>'get_casas_end_point_handler'
    ));
});

function get_casas_end_point_handler(){
    global $wpdb; 
    
    //parametros de la api rest
    $params = [
        'status' => isset($_GET['status']) ? $_GET['status'] : null, // numero minimo de huespedes
        'limit' => isset($_GET['limit']) ? $_GET['limit'] : 10
    ];

    // Pedimos todos los casas
    $query_casas = $wpdb->get_results("SELECT * from {$wpdb->prefix}posts WHERE post_type='casas' LIMIT 99999999999 "); 
    $count = 0;
    $casas=[]; 
    // Le asignamos los meta
    foreach($query_casas as $key => $cargo_especiales):
        $cargo_especiales->meta = get_post_custom($cargo_especiales->ID);
        $query_casas[$key] = $cargo_especiales;
        if( $cargo_especiales->post_status != 'draft' && $cargo_especiales->post_status != 'trash'){
            $count++ ;
        }
        if(isset($params['status']) && $cargo_especiales->post_status == $params['status']){
            $casas[$key] = $query_casas[$key];
            if(isset($params['limit']) && $key < intval($params['limit'])){
                $casas[$key] = $query_casas[$key];
            }
        }
        if(!isset($params['status']) && isset($params['limit']) && $cargo_especiales->post_status != 'draft' && $cargo_especiales->post_status != 'trash'){
            if($key < intval($params['limit'])){
                $casas[$key] = $query_casas[$key];
            }
        }
    endforeach;

    $response = json_encode(
            [
                'casas'=>$casas,
                'count' => $count
            ]
        );
    // devolvemos los datos en decodificando el json
    return json_decode($response);
}