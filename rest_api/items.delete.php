<?php
add_action("rest_api_init",function(){

    register_rest_route('wp-reserves-system/v1','/items',
    array(
        'methods'=>'delete',
        'permission_callback' => function() { return ''; },
        'callback'=>'wp_houses_reserve_delete_item'
    ));
});

function wp_houses_reserve_delete_item($request){
    global $wpdb; 
    
    //parametros de la api rest
    $params = json_decode($request->get_body());
    
    $wpdb->delete("{$wpdb->prefix}posts", array('post_type'=>$params->cpt,'ID' => $params->id));
    //se actualizan los datos
   
    $response = ['titulo'=>$params->id,'status'=>'ok'];
    return json_decode(json_encode($response));
   
}