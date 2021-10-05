<?php

function forms_reservas(){ 
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
	?>
<!-- Formulario para insertar un inmueble -->
<div class="container_cpanel_form">
		<div class="container_cpanel_new_reservas">
			<div>
				<h3>Shortcode: [shortcode_reservaciones]</h3>
				<p>Puede insertar el formulario el cualquier parte</p>

				<?php echo do_shortcode('[shortcode_reservaciones]') ?>
			</div>
			
			<div>
				<h3>Correos del personal autorizado</h3>
				<p>Se notificará sobre reservaciones a estas cuentas</p>

				<div class="container_utils">	
					<div>
						<input id="input_mail_insert" type="email" required>
						<button id="btn_mail_insert" >insertar</button>
					</div>
					<ul id="ul_emails" >
						<li>
							<p>**********@gmail.com</p>
							<button id="btn_mail_delete" >borrar</button>
						</li>
					</ul>
				</div>
				
			</div>
		</div>
		<div class="container_cpanel_edit_reservas">
			<div class="cpanel_form cpanel_edit_reservas">
				
			</div>
		</div>
	</div>

	<!-- Template forms reservas -->
	<template id="template_form_reservas_content" >
		<div>
			<input type="hidden" name="id" id="id" required >
		</div>
		<div>
			<label for="">titulo</label>
			<input type="text" name="titulo" id="titulo" required >
		</div>

		<!-- Dias de la semana-->
		
		<!-- Huespedes -->
		<div>
			<label for="">huespedes</label>
			<input type="number" name="huespedes" id="huespedes" required >
		</div>
		<div>
			<label for="">Cliente</label>
			<input type="text" name="cliente" id="cliente" required >
		</div>
        <div>
			<label for="">telefono</label>
			<input type="text" name="telefono" id="telefono" required >
		</div>
        <div>
			<label for="">correo</label>
			<input type="text" name="correo" id="correo" required >
		</div>
        <div>
			<label for="">checkin</label>
			<input type="date" name="checkin" id="checkin" required >
		</div>
        <div>
			<label for="">checkout</label>
			<input type="date" name="checkout" id="checkout" required >
		</div>
        <div>
			<label for="">monto</label>
			<input type="text" name="monto" id="monto" required >
		</div>
        <div>
			<label for="">observaciones</label>
			<input type="text" name="observaciones" id="observaciones" required >
		</div>
		<div>
			<label for="">Status</label>
			<select name="status" id="status">
				<option selected value=""></option>
				<option value="por-confirmar">por confirmar</option>
				<option value="confirmada">confirmada</option>
				<option value="cancelada" >cancelada</option>
				<option value="finalizada" >finalizada</option>
			</select>
		</div>
		<div>
			<button onclick="update_item(this)" data="reservas" style="margin-top: 0px !important;" class="button-primary right boton_cargos_especiales">Actualizar</button>
        </div> 
        <div>
			<button onclick="close_edit_form(this)" data="reservas" style="margin-top: 0px !important;" class="button-primary right"  >Cancelar</button>
		</div> 
	</template>

	<template id="template_emails_list">
		<li>
			<b>*******@gmai.com</b>
			<button id="btn_mail_delete" onclick="delete_email(this)" >borrar</button>
		</li>
	</template>
<?php }