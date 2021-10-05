<?php
add_action("rest_api_init",function(){

    register_rest_route('wp-reserves-system/v1','/items',
    array(
        'methods'=>'PUT',
        'permission_callback' => function() { return ''; },
        'callback'=>'wp_houses_reserve_update_item'
    ));
});

function wp_houses_reserve_update_item(WP_REST_Request $request){
    global $wpdb; 
    
    //parametros de la api rest
    $params = json_decode($request->get_body());
    $response = ['titulo'=>'','status'=>'wait'];
    if($params->cpt == 'tarifas'){
        $args = array(
            'ID' => $params->body_item->id,
            'post_status' => $params->body_item->status,
            'post_title' => 'tarifa: '.$params->body_item->min_huespedes . '-'.$params->body_item->max_huespedes,
        );
    
        //se actualizan los datos
        $updated_post_id = wp_update_post( $args );
    
        // 1- minimo de huespedes
        
        update_post_meta( $updated_post_id, 'min_huespedes', $params->body_item->min_huespedes );
         // 2- miximo de huespedes
       
        update_post_meta( $updated_post_id, 'max_huespedes', $params->body_item->max_huespedes);
    
        //Dias de la semana
    
        update_post_meta( $updated_post_id, 'day_0', $params->body_item->day_0 );
    
        
        update_post_meta( $updated_post_id, 'day_1', $params->body_item->day_1 );
    
        
        update_post_meta( $updated_post_id, 'day_2', $params->body_item->day_2 );
    
        
        update_post_meta( $updated_post_id, 'day_3', $params->body_item->day_3 );
    
        
        update_post_meta( $updated_post_id, 'day_4', $params->body_item->day_4 );
    
        
        update_post_meta( $updated_post_id, 'day_5', $params->body_item->day_5 );
    
        
        update_post_meta( $updated_post_id, 'day_6', $params->body_item->day_6 );

        update_post_meta( $updated_post_id, 'casa', $params->body_item->casa );
        
        $response['titulo'] = 'tarifa: '.$params->body_item->min_huespedes . '-'.$params->body_item->max_huespedes;
        $response['status'] = 'ok'; 
        // se retorma un mensaje exitoso
        return json_decode(json_encode($response));
    }
    if($params->cpt == 'reservas'){

        $args = array(
            'ID' => $params->body_item->id,
            'post_status' => $params->body_item->status,
            'post_title' => $params->body_item->titulo,
        );
    
        //insert the post by wp_insert_post() function
        $updated_post_id = wp_update_post( $args );
        //update_post_meta( $updated_post_id, 'titulo_inmueble', $params->body_item->id );
        update_post_meta( $updated_post_id, 'checkin', $params->body_item->checkin );
        update_post_meta( $updated_post_id, 'checkout', $params->body_item->checkout );
        update_post_meta( $updated_post_id, 'huespedes', $params->body_item->huespedes );
        update_post_meta( $updated_post_id, 'cliente', $params->body_item->cliente );
        update_post_meta( $updated_post_id, 'telefono', $params->body_item->telefono );
        update_post_meta( $updated_post_id, 'correo', $params->body_item->correo );
        update_post_meta( $updated_post_id, 'monto', $params->body_item->monto );
        update_post_meta( $updated_post_id, 'observaciones', $params->body_item->observaciones );
        update_post_meta( $updated_post_id, 'type_house', $params->body_item->type );

        $response['titulo'] = $params->body_item->titulo;
        $response['status'] = 'ok';
        return json_decode(json_encode($response));
    }
    if($params->cpt == 'cargos_especiales'){

        $args = array(
            'ID' => $params->body_item->id,
            'post_status' => $params->body_item->status,
            'post_title' => $params->body_item->titulo,
        );
    
        //insert the post by wp_insert_post() function
        $updated_post_id = wp_update_post($args);

        
        update_post_meta( $updated_post_id, 'fecha_especial', $params->body_item->fecha );

        
        update_post_meta( $updated_post_id, 'asunto', $params->body_item->asunto );

        
        update_post_meta( $updated_post_id, 'monto', $params->body_item->monto );

        $response['titulo'] = $params->body_item->titulo;
        $response['status'] = 'ok';
        return json_decode(json_encode($response));
    }
    if($params->cpt == 'casas'){

        $args = array(
            'ID' => $params->body_item->id,
            'post_status' => $params->body_item->status,
            'post_title' => $params->body_item->titulo,
        );
    
        //insert the post by wp_insert_post() function
        $updated_post_id = wp_update_post($args);

        $response['titulo'] = $params->body_item->titulo;
        $response['status'] = 'ok';
        return json_decode(json_encode($response));
    }
}