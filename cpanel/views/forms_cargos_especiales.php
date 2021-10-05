<?php 

function form_cargos_especiales(){ ?>
	<!-- Formulario para insertar un inmueble -->
	<div class="container_cpanel_form">
		<div class="container_cpanel_new_cargos_especiales">
			<div class="cpanel_form cpanel_new_cargos_especiales order_form_cargos_especiales">
				<div>
					<label for="">titulo</label>
					<input type="text" name="titulo" id="titulo" required="">
				</div>

				
				<div>
					<label for="">fecha</label>
					<input type="date" name="fecha" id="fecha" required="">
				</div>
				<div>
					<label for="">asunto</label>
					<input type="text" name="asunto" id="asunto" required="">
				</div>
				<div>
					<label for="">Monto</label>
					<input type="number" name="monto" id="monto" required="">
				</div>
				
				<div>
					<button onclick="create_item(this)" data="cargos_especiales" class="button-primary boton_cargos_especiales right">Crear cargo especial</button>
				</div> 
			</div>
		</div>
		
		<div class="container_cpanel_edit_cargos_especiales">
		<hr>
			<div class="cpanel_form cpanel_edit_cargos_especiales ">
				
			</div>
		</div>
	</div>
	

	<!-- Template forms cargos_especiales -->
	<template id="template_form_cargos_especiales_content" >
		<div>
			<input type="hidden" name="id" id="id" required >
		</div>
		<div>
            <label for="">titulo</label>
            <input type="text" name="titulo" id="titulo" required="">
        </div>

        
        <div>
            <label for="">fecha</label>
            <input type="date" name="fecha" id="fecha" required="">
        </div>
        <div>
            <label for="">asunto</label>
            <input type="text" name="asunto" id="asunto" required="">
        </div>
        <div>
            <label for="">Monto</label>
            <input type="number" name="monto" id="monto" required="">
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
			<button onclick="update_item(this)" data="cargos_especiales" class="button-primary boton_cargos_especiales" >Actualizar</button>
		</div> 
		<div>
			<button onclick="close_edit_form(this)" data="cargos_especiales" class="button-primary right cerrar_especiales" >Cancelar</button>
		</div> 
	</template>
<?php }