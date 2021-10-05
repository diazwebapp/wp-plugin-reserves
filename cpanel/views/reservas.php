<?php
function window_reservas(){ ?>
	<table cellspacing="0" class="table_items table_reservas wp-list-table widefat striped table-view-list posts" >
		<thead>
			<tr>
				<td class=" manage-column column-cb check-column">N</td>
				<td class=" manage-column column-cb check-column">ID</td> 
				<td >Casa</td>
				<td class="td_huespedes_reservas" >huespedes</td>
				<td>cliente</td>
				<td class="td_telefono_reservas">telefono</td>
				<td class="td_correo_reservas">correo</td>
				<td>checkin</td>
				<td>checkout</td>
				<td>monto</td>
				<td>status</td>
				<td class="td_observaciones_reservas">observaciones</td>
				<td class="td_actions">Acciones</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class=" manage-column column-cb check-column">N</td>
				<td class=" manage-column column-cb check-column">ID</td> 
				<td class=" manage-column column-categories">titulo</td>
				<td>huespedes</td>
				<td>cliente</td>
				<td>telefono</td>
				<td>correo</td>
				<td>checkin</td>
				<td>checkout</td>
				<td>monto</td>
				<td>observaciones</td>
				<td>status</td>
				<td class="td_actions">
					...
				</td>
			</tr>
		</tbody>
	</table>
	<div  id="paginate_reservas" class="container_pagination">
		
	</div>
	<!-- Tempate TR table tomado con js para ser procesado en la tabla de arriba -->
	<template id="template_tbody_reservas_content">
		<tr>
			<td class=" manage-column column-cb check-column">N</td>
			<td class=" manage-column column-cb check-column">ID</td> 
			<td >Casa</td>
			<td class="td_huespedes_reservas"></td>
			<td></td>
			<td class="td_telefono_reservas"></td>
			<td class="td_correo_reservas"></td>
			<td></td>
			<td></td>
			<td></td>
			<td class="td_observaciones_reservas"></td>
			<td></td>
			<td class="td_actions">
				<button onclick="view_item(this)" data="reservas" class="button action">Ver</button>
				<button onclick="delete_item(this)" data="reservas" class="button action borrar">Borrar</button>
			</td>
		</tr>
	</template>

	
<?php }