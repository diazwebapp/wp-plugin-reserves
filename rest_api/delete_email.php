<?php
add_action("rest_api_init",function(){

    register_rest_route('wp-reserves-system/v1','/delete_email',
    array(
        'methods'=>'delete',
        'permission_callback' => function() { return ''; },
        'callback'=>'wp_houses_reserve_delete_email'
    ));
});

function wp_houses_reserve_delete_email($request){
    global $wpdb; 
    
    //parametros de la api rest
    $params = json_decode($request->get_body());
    
    $wpdb->delete("{$wpdb->prefix}reserves_system_emails_admin", array('ID' => $params->email_id));
    //se actualizan los datos
   
    $response = ['titulo'=>$params->email_id,'status'=>'ok'];
    return json_decode(json_encode($response));
   
}