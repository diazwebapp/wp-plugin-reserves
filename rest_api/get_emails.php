<?php
add_action("rest_api_init",function(){

    register_rest_route('wp-reserves-system/v1','/emails',
    array(
        'methods'=>'GET',
        'permission_callback' => function() { return ''; },
        'callback'=>'wp_houses_reserve_get_emails'
    ));
});

function wp_houses_reserve_get_emails(){
    global $wpdb; 
    
    //parametros de la api rest
    $params = [
        'status' => isset($_GET['status']) ? $_GET['status'] : null, // numero minimo de huespedes
    ];

    $registros = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}reserves_system_emails_admin " );

    $response = ['total'=>count($registros),'emails'=>$registros];
    return json_decode(json_encode($response)); 
    
}