<?php
add_action("rest_api_init",function(){

    register_rest_route('wp-reserves-system/v1','/items',
    array(
        'methods'=>'POST',
        'permission_callback' => function() { return ''; },
        'callback'=>'wp_houses_reserve_create_inmueble'
    ));
});

function wp_houses_reserve_create_inmueble(WP_REST_Request $request){
    global $wpdb; 
    
    //parametros de la api rest
    $params = json_decode($request->get_body());
    $response = ['titulo'=>'','status'=>'wait','id' =>''];
    if($params->cpt == 'tarifas'){
        $query_tarifas = $wpdb->get_results("SELECT * from {$wpdb->prefix}posts WHERE post_type='tarifas' AND post_status='publish' LIMIT 9999999999"); 
        $current_tarifa; 
        // Le asignamos los meta
        foreach($query_tarifas as $key => $tarifa):
            $fields = get_post_custom($tarifa->ID);
            $tarifa->meta = get_post_meta($tarifa->ID,$fields[$key]);
            
            $query_tarifas[$key] = $tarifa;
            if($query_tarifas[$key]->meta['max_huespedes'][0] >= $params->body_item->max_huespedes && $query_tarifas[$key]->meta['min_huespedes'][0] <= $params->body_item->min_huespedes){
                $current_tarifa = $query_tarifas[$key];
            }
        endforeach;

        if(isset($current_tarifa) && !empty($current_tarifa)):
            $response['titulo'] = $current_tarifa->post_title;
            $response['status'] = 'duplicate';
            return json_decode(json_encode($response));
        endif;
        $args = array(
            'post_status' => 'publish',
            'post_title' => 'tarifa: '.$params->body_item->min_huespedes . '-'.$params->body_item->max_huespedes,
            'post_type' => $params->cpt
        );
    
        //insert the post by wp_insert_post() function
        $new_post_id = wp_insert_post($args);
    
        // 1- minimo de huespedes
        
        update_post_meta( $new_post_id, 'min_huespedes', $params->body_item->min_huespedes );
         // 2- miximo de huespedes
       
        update_post_meta( $new_post_id, 'max_huespedes', $params->body_item->max_huespedes);
    
        //Dias de la semana
    
        update_post_meta( $new_post_id, 'day_0', $params->body_item->day_0 );
        
        update_post_meta( $new_post_id, 'day_1', $params->body_item->day_1 );
    
        update_post_meta( $new_post_id, 'day_2', $params->body_item->day_2 );
        
        update_post_meta( $new_post_id, 'day_3', $params->body_item->day_3 );
        
        update_post_meta( $new_post_id, 'day_4', $params->body_item->day_4 );
        
        update_post_meta( $new_post_id, 'day_5', $params->body_item->day_5 );
        
        update_post_meta( $new_post_id, 'day_6', $params->body_item->day_6 );

        update_post_meta( $new_post_id, 'casa', $params->body_item->casa );
        
        $response['titulo'] ='tarifa: '.$params->body_item->min_huespedes . '-'.$params->body_item->max_huespedes;
        $response['status'] = 'ok';
        $response['id'] = $new_post_id;
        return json_decode(json_encode($response));
    }
    if($params->cpt == 'cargos_especiales'){
        $args = array(
            'post_status' => 'publish',
            'post_title' => $params->body_item->titulo,
            'post_type' => $params->cpt
        );
    
        //insert the post by wp_insert_post() function
        $new_post_id = wp_insert_post($args);

        
        update_post_meta( $new_post_id, 'fecha_especial', $params->body_item->fecha );
        update_post_meta( $new_post_id, 'asunto', $params->body_item->asunto );
        update_post_meta( $new_post_id, 'monto', $params->body_item->monto );

        $response['titulo'] = $params->body_item->asunto;
        $response['status'] = 'ok';
        $response['id'] = $new_post_id;

        return json_decode(json_encode($response));
     
    }
    if($params->cpt == 'casas'){
        $query_casas = $wpdb->get_results("SELECT * from {$wpdb->prefix}posts WHERE post_type='casas' AND post_status='publish' AND post_title='{$params->body_item->titulo}' LIMIT 9999999999");
        if(isset($query_casas[0])):
            $response['titulo'] = $params->body_item->titulo;
            $response['status'] = isset($query_casas[0]) ? 'fail':'ok';
            return json_decode(json_encode($response));
        endif;
        $args = array(
            'post_status' => 'publish',
            'post_title' => $params->body_item->titulo,
            'post_type' => $params->cpt
        );
    
        //insert the post by wp_insert_post() function
        $new_post_id = wp_insert_post($args);

        $response['titulo'] = $params->body_item->titulo;
        $response['status'] = isset($query_casas[0]) ? 'fail':'ok';
        $response['id'] = $new_post_id;

        return json_decode(json_encode($response));
     
    }
    // devolvemos los datos en decodificando el json
}