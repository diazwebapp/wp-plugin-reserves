<?php
function form_casas(){ ?>
	<!-- Formulario para insertar un inmueble -->
	<div class="container_cpanel_form">
		<div class="container_cpanel_new_casas">
			<div class="cpanel_form cpanel_new_casas order_form_casas">
				<div>
					<label for="">Nombre</label>
					<input type="text" name="titulo" id="titulo" required="">
				</div>
				
				<div>
					<button onclick="create_item(this)" data="casas" class="button-primary boton_cargo_especiales btn_margen">Crear casa</button>
				</div> 
			</div>

			<div>
				<h2>Instruciones</h2> 
				<ol> 
					<li>Ingrese el nombre de la casa que desea agregar</li>
					<li>verifique el nombre de la casa</li>
					<li>Precione crear casa</li>
				</ol>

				<p><i>NOTA: Si la casa no aparece en la tabla de abajo por favor recargue la pagina</i></p>
			</div>
		</div>
		
		

		<div class="container_cpanel_edit_casas">
			<div class="cpanel_form cpanel_edit_casas">
				
			</div>
		</div>
	</div>
	

	<!-- Template forms casas -->
	<template id="template_form_casas_content" >
		<div>
			<input type="hidden" name="id" id="id" required >
		</div>
		<div>
            <label for="">nombre</label>
            <input type="text" name="titulo" id="titulo" required="">
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
			<button onclick="update_item(this)" data="casas" class="button-primary boton_cargo_especiales right boton_cargos_especiales" >Actualizar</button>
		</div> 
		<div>
			<button onclick="close_edit_form(this)" data="casas" class="button-primary right" >Cancelar</button>
		</div> 
	</template>
<?php }