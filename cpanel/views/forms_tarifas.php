<?php

function form_tarifas(){ global $wpdb;
	$query_casas = $wpdb->get_results("SELECT * from {$wpdb->prefix}posts WHERE post_type='casas' AND post_status='publish' LIMIT 99999999999"); ?>
	<!-- Formulario para insertar un inmueble -->
	<div class="container_cpanel_form">
		<div class="container_cpanel_new_tarifas">
			<div class="cpanel_form cpanel_new_tarifas order_form_new_tarifas">

				<!-- Dias de la semana-->
				
				<div>
					<label class="" for="">Domingo</label>
					<input type="number" name="day_0" id="day_0" required="">
				</div>
				<div>
					<label class="" for="">Lunes</label>
					<input type="number" name="day_1" id="day_1" required="">
				</div>
				<div>
					<label class="" for="">Martes</label>
					<input type="number" name="day_2" id="day_2" required="">
				</div>
				<div>
					<label class="" for="">Miercoles</label>
					<input type="number" name="day_3" id="day_3" required="">
				</div>
				<div>
					<label class="" for="">Jueves</label>
					<input type="number" name="day_4" id="day_4" required="">
				</div>
				<div>
					<label for="">Viernes</label>
					<input type="number" name="day_5" id="day_5" required="">
				</div>
				<div>
					<label for="">Sabado</label>
					<input type="number" name="day_6" id="day_6" required="">
				</div>
				<!-- Huespedes -->
				<div>
					<label for="">huespedes minimos</label>
					<input type="number" name="min_huespedes" id="min_huespedes" required="">
				</div>
				<div>
					<label for="">Huespedes maximos</label>
					<input type="number" name="max_huespedes" id="max_huespedes" required="">
				</div>
				<div>
					<label for="">Casa</label>
					<select name="casa" id="casa">
						
					</select>
				</div>
				<div>
					<button onclick="create_item(this)" data="tarifas" class="right button-primary">Crear tarifa</button>
				</div> 
			</div>

			<div>
			<h2>Instruciones</h2> 
			<p>En este apartado se añade una taria para los diferentes tipos de casa</p> 
				<ol> 
					<li>Selecionar la cantidad minima y maxima de huespedes</li>
					<li>Selecionar la casa a la cual se le aplicara el precio</li>
					<li>Ingresar los cargos por dias de la semana</li>
					<li>comprobar los datos ingresados </li>
					<li>Crear la tarifa</li>
				</ol>

				<p><i>NOTA: Si la casa no aparece en la tabla de abajo por favor recargue la pagina</i></p>
			</div>
		</div>

		
		<div class="container_cpanel_edit_tarifas">
		<hr>
			<div class="cpanel_form cpanel_edit_tarifas">
				
			</div>
		</div>
	</div>
	

	<!-- Template forms tarifas -->
	<template id="template_form_tarifas_content" >
		<div>
			<input type="hidden" name="id" id="id" required >
		</div>

		<!-- Dias de la semana-->
		
		<div>
			<label for="">Domingo</label>
			<input type="number" name="day_0" id="day_0" required >
		</div>
		<div>
			<label for="">Lunes</label>
			<input type="number" name="day_1" id="day_1" required >
		</div>
		<div>
			<label for="">Martes</label>
			<input type="number" name="day_2" id="day_2" required >
		</div>
		<div>
			<label for="">Miercoles</label>
			<input type="number" name="day_3" id="day_3" required >
		</div>
		<div>
			<label for="">Jueves</label>
			<input type="number" name="day_4" id="day_4" required >
		</div>
		<div>
			<label for="">Viernes</label>
			<input type="number" name="day_5" id="day_5" required >
		</div>
		<div>
			<label for="">Sabado</label>
			<input type="number" name="day_6" id="day_6" required >
		</div>
		<!-- Huespedes -->
		<div>
			<label for="">huespedes minimos</label>
			<input type="number" name="min_huespedes" id="min_huespedes" required >
		</div>
		<div>
			<label for="">Huespedes maximos</label>
			<input type="number" name="max_huespedes" id="max_huespedes" required >
		</div>
		<div>
			<label for="">Status</label>
			<select name="status" id="status">
				<option selected value=""></option>
				<option value="pending">pendiente</option>
				<option value="publish">publicada</option>
			</select>
		</div>
		<div>
			<label for="">Casa</label>
			<select name="casa" id="casa">
				<option selected value=""></option>
				<?php
					foreach($query_casas as $casa):
						echo '<option value="'.$casa->post_title.'">'.$casa->post_title.'</option>';
					endforeach; 
				?>
			</select>
		</div>
		<div>
			<button onclick="update_item(this)" data="tarifas" class="right button-primary boton_cargos_especiales">Actualizar</button>
		</div> 
		<div>
			<button onclick="close_edit_form(this)" data="tarifas" class="button-primary right" >Cancelar</button>
		</div> 
	</template>
	
<?php }