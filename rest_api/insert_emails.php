<?php
add_action("rest_api_init",function(){

    register_rest_route('wp-reserves-system/v1','/new_email',
    array(
        'methods'=>'POST',
        'permission_callback' => function() { return ''; },
        'callback'=>'wp_houses_reserve_insert_email'
    ));
});

function wp_houses_reserve_insert_email(WP_REST_Request $request){
    global $wpdb; 
    
    //parametros de la api rest
    $params = json_decode($request->get_body());

    $registros = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}reserves_system_emails_admin where email='$params->email' " );

    if(!$registros[0]){

      $wpdb->insert( $wpdb->prefix.'reserves_system_emails_admin', 
      
        array( 
          'nombre' => '', 
          'email' => $params->email 
        )
      ); 
      $new_email = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}reserves_system_emails_admin where email='$params->email' " );
      
      $response = ['email'=>$params->email,'status'=>'insertado'];
      return json_decode(json_encode($response));
    }

    $response = ['email'=>$params->email,'status'=>'ya existe'];
    return json_decode(json_encode($response)); 
    
}