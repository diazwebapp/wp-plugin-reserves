<?php
add_action("rest_api_init",function(){

    register_rest_route('wp-reserves-system/v1','/prepare_reserve',
    array(
        'methods'=>'GET',
        'callback'=>'prepare_reserve_handler'
    ));
});

function prepare_reserve_handler(){
    global $wpdb; 
    
    //parametros de la api rest
    $params = [
        'persons' => isset($_GET['persons'])?$_GET['persons']:null, // numero minimo de huespedes
        'checkin' => isset($_GET['checkin'])?$_GET['checkin']:null,
        'checkout' => isset($_GET['checkout'])?$_GET['checkout']:null,
    ];

    // Pedimos todos los inmuebles
    $query_tarifas = $wpdb->get_results("SELECT * from {$wpdb->prefix}posts WHERE post_type='tarifas' AND post_status='publish' LIMIT 9999999999"); 
    $current_tarifa;
    $cant_tarifas = count($query_tarifas) ;
    // Le asignamos los meta
    if($cant_tarifas > 0){
        foreach($query_tarifas as $key => $tarifa):
            $tarifa->meta = get_post_custom($tarifa->ID);
            
            $query_tarifas[$key] = $tarifa;
            if($query_tarifas[$key]->meta['max_huespedes'][0] >= $params['persons'] && $query_tarifas[$key]->meta['min_huespedes'][0] <= $params['persons']){
                $current_tarifa = $query_tarifas[$key];
            }
    
        endforeach;
    }
    // si existe checkin y checkout se procesará un array de fechas 
    if(isset($params['checkin']) && isset($params['checkout'])):
        $fecha1 = $params['checkin'];
        $fecha2 = $params['checkout'];
        $reserve_dates = [];
        
        $indice=0;
        for($i=$fecha1;$i<=$fecha2;$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
            $reserve_dates[$indice] =  $i ;
            $indice++;
        }

        $indice2=0;
        for($i=$fecha1;$i<=$fecha2;$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
            $reserve_dates[$indice2] =  $i ;
            $indice2++;
        }
    endif;
    $query_cargos_especiales = $wpdb->get_results("SELECT * from {$wpdb->prefix}posts WHERE post_type='cargos_especiales' AND post_status='publish' LIMIT 9999999999"); 
    $cargos_especiales=[];
     // Le asignamos los meta a las cargos
     if(count($query_cargos_especiales) > 0){
        foreach($query_cargos_especiales as $key => $reserve):
            $cargos_especiales[$key] = get_post_custom($reserve->ID);
        endforeach;
    }

     /*------------- consultamos las reservas ----------------*/
    $query_reservas = $wpdb->get_results("SELECT * from {$wpdb->prefix}posts WHERE post_type='reservas' LIMIT 99999999999");
    $cant_reservas = count($query_reservas);
    // Le asignamos los meta
    $indice_5=0;
    $reserve_dates_server=[];
    if($cant_reservas > 0){
        foreach($query_reservas as $key => $reserva):
            $reserva->meta = get_post_custom($reserva->ID);
            $query_reservas[$key] = $reserva;
            if(isset($reserva->meta['casa']) && $current_tarifa->meta['casa'][0] == $reserva->meta['casa'][0] ){
                if($reserva->post_status == 'por-confirmar' || $reserva->post_status == 'confirmada'){
                    for($i=$reserva->meta['checkin'][0];$i<=$reserva->meta['checkout'][0];$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
                        $reserve_dates_server[$indice_5] =  $i ;
                        $indice_5++;
                    }
                }
            }
        endforeach;
    }
    $response = json_encode(
            [
                'tarifa'=>$current_tarifa,
                "reserve_dates" => isset($reserve_dates)?$reserve_dates:[],
                'cargos_especiales' => $cargos_especiales,
                'reserve_dates_server' => $reserve_dates_server
            ]
        );
    // devolvemos los datos en decodificando el json
    return json_decode($response);
}