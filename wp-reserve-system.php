<?php
/**
 * Plugin Name: Sistema De Reservas a la medida.
 * Description: Este plugins esta desarrollado a la medida para hacer una reserva fltrada de las casas idsponibles.
 * Author: Jesus medina & Diaz web app
 * Author URI: https://impulsocomercial.website
 * Plugin URI: https://impulsocomercial.website
 * Version: 1.3.0
 * License: ISC
 * Text Domain: Sistema De Reservas a la medida.
 */
define( 'PLUGIN_DIR', plugin_dir_path( __FILE__ ) ); //sirve para importar .php
define( 'PLUGIN_DIR_2', plugin_dir_url(__FILE__) ); //Sirve para importar los js y los css

require_once PLUGIN_DIR . 'cpt/cpt_casas.php';
require_once PLUGIN_DIR . 'cpt/cpt_tarifas.php';
require_once PLUGIN_DIR . 'cpt/cpt_cargos_especiales.php';
require_once PLUGIN_DIR . 'cpt/cpt_reservas.php';
require_once PLUGIN_DIR . 'cpanel/cpanel.php';
//rest api functions
require_once PLUGIN_DIR . 'rest_api/prepare_reserve.php'; // obtiene una habitacion
require_once PLUGIN_DIR . 'rest_api/create_reserve.php'; // crea una reservacion
require_once PLUGIN_DIR . 'rest_api/get_tarifas_cpanel.php'; // obtiene varios tarifas
require_once PLUGIN_DIR . 'rest_api/items.create.php'; // crea un inmueble
require_once PLUGIN_DIR . 'rest_api/items.update.php'; // actualiza
require_once PLUGIN_DIR . 'rest_api/items.delete.php'; // actualiza
require_once PLUGIN_DIR . 'rest_api/get_reservas_cpanel.php'; // obtiene las reservaciones filtradas
require_once PLUGIN_DIR . 'rest_api/get_cargos_especiales_cpanel.php'; // obtiene las cargos_especiales filtradas
require_once PLUGIN_DIR . 'rest_api/get_casas_cpanel.php'; // obtiene varios casas
require_once PLUGIN_DIR . 'rest_api/insert_emails.php'; // inserta nuevos emails
require_once PLUGIN_DIR . 'rest_api/get_emails.php'; // obtiene los emails
require_once PLUGIN_DIR . 'rest_api/delete_email.php'; // elimina un email
//email_template
require_once PLUGIN_DIR . 'cpanel/email.php';

function func_ui_reservaciones_cliente(){
    wp_enqueue_style( 'ewd-uasp-jquery-datepicker-css', PLUGIN_DIR_2 . 'css/shortcode_reservaciones.css' );
    wp_enqueue_style( 'calendar', PLUGIN_DIR_2 . 'css/calendar.css' );
    wp_register_script('moment', PLUGIN_DIR_2 . 'js/moment.min.js', '1', true);
    wp_enqueue_script('moment');
    wp_register_script('calendar', PLUGIN_DIR_2 . 'js/calendar.js', '1', true);
    wp_enqueue_script('calendar');
    wp_register_script('reservas_cliente', PLUGIN_DIR_2 . 'js/shortcode_reservaciones.js', '1', true);
    wp_enqueue_script('reservas_cliente');
    wp_localize_script( 'reservas_cliente', 'wp_rest_api', array(
        'root' => esc_url_raw( rest_url() ),
        'nonce' => wp_create_nonce( 'wp_rest' )
    ) );
    
    $html = '<section id="ventana" >
               
                <div class="step_1" >
                    
                    <div>
                        <label>'. __('Nombre','wp-reserves-system').'</label>
                        <input type="text" name="nombre" id="nombre" value="" />
                    </div>
                    <div>
                        <label>Número de teléfono</label>
                        <input type="text" name="telefono" id="telefono" value="" />
                    </div>

                    <div>
                        <label>Correo</label>
                        <input type="email" name="correo" id="correo" value="" />
                    </div>
                    <div>
                        <label>Huespedes</label>
                        <input type="number" name="cant_huespedes" id="cant_huespedes" />
                    </div>
                    <div>
                        <label>observaciones</label>
                        <textarea name="observaciones" id="observaciones"></textarea />
                    </div>
                    <button id="btn_buscar_casas" class="button button-primary" >'. __('Comprobar disponibilidad','wp-reserves-system').'</button>
                </div>

                <div class="step_2" >
                <h2>Selecione su fecha</h2>
                    <div class="container_calendar" >
                        <div id="calendarCheckin"></div>
                    </div>
                    <div class="btn_calendario">   
                    <button id="btn_to_step_1" class="button-danger" >'. __('Cancelar','wp-reserves-system').'</button>
                    <button id="btn_to_step_3"  >'. __('Siguiente','wp-reserves-system').'</button>
                    </div>
                </div>
                <div class="step_3" >
                    <article>
                        <h2>Confirmar datos de Reserva</h2>
                        <ul>
                            <li>Cliente: </li>
                            <li>Correo: </li>
                            <li>Teléfono: </li>
                            <li>Casa: </li>
                            <li>Checking: </li>
                            <li>Checkout: </li>
                            <li>N° de huespedes: </li>
                            
                        </ul>
                        <br>
                        <hr>
                        <p class="precio">Precio: </p>
                    </article>
                    <div class="btn_calendario">  
                    <button id="btn_to_step_2" class="button-danger" >'. __('Cancelar','wp-reserves-system').'</button>
                    <button id="btn_finish" >'. __('Completar','wp-reserves-system').'</button>
                    </div>
                    </div>
            </section>
    
    ';  
    
    return $html ;
}
add_shortcode('shortcode_reservaciones','func_ui_reservaciones_cliente');

function crear_base() {
  
    global $wpdb;
    
      // Con esto creamos el nombre de la tabla y nos aseguramos que se cree con el mismo prefijo que ya tienen las otras tablas creadas (wp_form).
      $table_name = $wpdb->prefix . 'reserves_system_emails_admin';
   
      // Declaramos la tabla que se creará de la forma común.
      $sql = "CREATE TABLE $table_name (
        `ID` int(11) NOT NULL AUTO_INCREMENT,
        `nombre` varchar(255),
        `email` varchar(255) NOT NULL,
        UNIQUE KEY ID (ID)
      );";
  
      // upgrade contiene la función dbDelta la cuál revisará si existe la tabla.
      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  
      // Creamos la tabla
      dbDelta($sql);
  
  }
  crear_base();