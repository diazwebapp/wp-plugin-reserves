<?php
add_action("rest_api_init",function(){

    register_rest_route('wp-reserves-system/v1','/create_reserve',
    array(
        'methods'=>'POST',
        'permission_callback' => function() { return ''; },
        'callback'=>'end_point_wp_houses_reserve_create_reserve'
    ));
});

function end_point_wp_houses_reserve_create_reserve(WP_REST_Request $request){
    
    global $wpdb; 
    $objDateTime = new DateTime();
    //parametros de la api rest
    $params = json_decode($request->get_body());

    $args = array(
        'post_status' => 'por-confirmar',
        'post_title' => $params->factura->tarifa,
        'post_type' => 'reservas'
    );

    //Se insertan los datos
    $new_post_id = wp_insert_post($args);
    // se insertan los metas
    update_post_meta( $new_post_id, 'checkin', $params->factura->checkin );
    update_post_meta( $new_post_id, 'checkout', $params->factura->checkout );
    update_post_meta( $new_post_id, 'huespedes', $params->factura->huespedes );
    update_post_meta( $new_post_id, 'cliente', $params->cliente->nombre );
    update_post_meta( $new_post_id, 'telefono', $params->cliente->telefono );
    update_post_meta( $new_post_id, 'correo', $params->cliente->correo );
    update_post_meta( $new_post_id, 'monto', $params->factura->total );
    update_post_meta( $new_post_id, 'observaciones', $params->factura->observaciones );
    update_post_meta( $new_post_id, 'casa', $params->factura->casa );
    
    
    //Enviando datos al correo
    function mail_contenido_html() {
        return "text/html";
    }
    add_filter( "wp_mail_content_type", "mail_contenido_html" );
    $data = [
        'checkin'=>$params->factura->checkin,
        'checkout'=>$params->factura->checkout,
        'huespedes'=>$params->factura->huespedes,
        'cliente'=>$params->cliente->nombre,
        'telefono'=>$params->cliente->telefono,
        'correo'=>$params->cliente->correo,
        'monto'=>$params->factura->total,
        'observaciones'=>$params->factura->observaciones,
        'casa'=>$params->factura->casa,
    ];
    $email_body = email_body($data);

    $headers[] = "From:" . get_bloginfo( 'name' ) ." <". get_bloginfo( 'admin_email' ). ">";
    wp_mail($params->cliente->correo, "Su reserva a sido creada con éxito", $email_body, $headers );

    // se solicitan los emails de adminitradores
    $query_mails = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}reserves_system_emails_admin " );
    $emails_admin = [];

    for($data=0; $data < count($query_mails); $data++):
        $emails_admin[$data] = $query_mails[$data]->email ; 
    endfor;

    wp_mail($emails_admin, "Se ha realizado una reserva", $email_body, $headers );
    
    $response = json_encode(["status"=>"ok"]);
    // devolvemos los datos en decodificando el json
    return json_decode($response);
}
