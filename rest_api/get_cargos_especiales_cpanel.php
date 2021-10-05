<?php
add_action("rest_api_init",function(){

    register_rest_route('wp-reserves-system/v1','/cargos_especiales',
    array(
        'methods'=>'GET',
        'callback'=>'get_cargos_especiales_end_point_handler'
    ));
});

function get_cargos_especiales_end_point_handler(){
    global $wpdb; 
    
    //parametros de la api rest
    $params = [
        'status' => isset($_GET['status']) ? $_GET['status'] : null, // numero minimo de huespedes
        'limit' => isset($_GET['limit']) ? $_GET['limit'] : 10
    ];

    // Pedimos todos los cargos_especiales
    $query_cargos_especiales = $wpdb->get_results("SELECT * from {$wpdb->prefix}posts WHERE post_type='cargos_especiales' ORDER BY ID DESC LIMIT 99999999999"); 
    $count = 0;
    $cargos_especiales=[]; 
    // Le asignamos los meta
    foreach($query_cargos_especiales as $key => $cargo_especiales):
        $cargo_especiales->meta = get_post_custom($cargo_especiales->ID);
        $query_cargos_especiales[$key] = $cargo_especiales;
        if( $cargo_especiales->post_status != 'draft' && $cargo_especiales->post_status != 'trash'){
            $count++ ;
        }
        if(isset($params['status']) && $cargo_especiales->post_status == $params['status']){
            $cargos_especiales[$key] = $query_cargos_especiales[$key];
            if(isset($params['limit']) && $key < intval($params['limit'])){
                $cargos_especiales[$key] = $query_cargos_especiales[$key];
            }
        }
        if(!isset($params['status']) && isset($params['limit']) && $cargo_especiales->post_status != 'draft' && $cargo_especiales->post_status != 'trash'){
            if($key < intval($params['limit'])){
                $cargos_especiales[$key] = $query_cargos_especiales[$key];
            }
        }
    endforeach;

    $response = json_encode(
            [
                'cargos_especiales'=>$cargos_especiales,
                'count' => $count
            ]
        );
    // devolvemos los datos en decodificando el json
    return json_decode($response);
}