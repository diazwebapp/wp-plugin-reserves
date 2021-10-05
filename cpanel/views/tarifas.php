<?php
function window_tarifa(){ ?>
	
	<table cellspacing="0" class="table_items table_tarifas wp-list-table widefat striped table-view-list posts" >
		<thead>
			<tr>
				<td class=" manage-column column-cb check-column">N</td>
				<td class=" manage-column column-cb check-column">ID</td> 
				<td class=" manage-column column-categories">titulo</td>
				<td class="td_day_tarifa manage-column column-comments ">dom</td>
				<td class="td_day_tarifa manage-column column-comments ">lun</td>
				<td class="td_day_tarifa manage-column column-comments ">mar</td>
				<td class="td_day_tarifa manage-column column-comments ">mie</td>
				<td class="td_day_tarifa manage-column column-comments ">jue</td>
				<td class="td_day_tarifa manage-column column-comments ">vie</td>
				<td class="td_day_tarifa manage-column column-comments ">sab</td>
				<td class="td_monto_tarifa">monto</td>
				<td class="td_min_tarifa manage-column column-comments">min</td>
				<td class="td_max_tarifa manage-column column-comments">max</td>
				<td class="td_status_tarifa manage-column ">status</td>
				<td class="td_type_tarifa manage-column ">Casa</td>
				<td class="td_actions">Acciones</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>...</td>
				<td>...</td>
				<td>...</td>
				<td class="td_day_tarifa">...</td>
				<td class="td_day_tarifa">...</td>
				<td class="td_day_tarifa">...</td>
				<td class="td_day_tarifa">...</td>
				<td class="td_day_tarifa">...</td>
				<td class="td_day_tarifa">...</td>
				<td class="td_day_tarifa">...</td>
				<td class="td_monto">...</td>
				<td class="td_min">...</td>
				<td class="td_max">...</td>
				<td class="td_status_tarifa">...</td>
				<td class="td_type_tarifa">...</td>
				<td class="td_actions">
					...
				</td>
			</tr>
		</tbody>
	</table>
	<div  id="paginate_tarifas" class="container_pagination">
		
	</div>
	<!-- Tempate TR table tomado con js para ser procesado en la tabla de arriba -->
	<template id="template_tbody_tarifas_content">
		<tr>
			<td class=" manage-column column-cb check-column"></td>
			<td class=" manage-column column-cb check-column"></td>
			<td class=" manage-column column-categories"></td>
			<td class="td_day_tarifa manage-column column-comments ">...</td>
			<td class="td_day_tarifa manage-column column-comments ">...</td>
			<td class="td_day_tarifa manage-column column-comments ">...</td>
			<td class="td_day_tarifa manage-column column-comments ">...</td>
			<td class="td_day_tarifa manage-column column-comments ">...</td>
			<td class="td_day_tarifa manage-column column-comments ">...</td> 
			<td class="td_day_tarifa manage-column column-comments ">...</td>
			<td class="td_monto_tarifa"></td>
			<td class="td_min_tarifa manage-column column-comments"></td>
			<td class="td_max_tarifa manage-column column-comments"></td>
			<td class="td_status_tarifa manage-column ">...</td>
			<td class="td_type_tarifa manage-column ">...</td>
			<td class="td_actions ">
				<button onclick="view_item(this)" data="tarifas" class="button action">Ver</button>
				<button onclick="delete_item(this)" data="tarifas" class="button action borrar">Borrar</button>
			</td>
		</tr>
	</template>
	
<?php }