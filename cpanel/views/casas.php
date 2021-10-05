<?php
function window_casas(){ ?>
    <table cellspacing="0" class="table_items table_casas wp-list-table widefat striped table-view-list posts" >
		<thead>
			<tr>
			<td class=" manage-column column-cb check-column">N</td>
				<td class=" manage-column column-cb check-column">ID</td> 
				<td class=" manage-column column-categories">titulo</td>
				<td>status</td>
				<td class="td_actions">Acciones</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>n</td>
				<td>id</td>
				<td>titulo</td>
				<td>status</td>
				<td class="td_actions">
					...
				</td>
			</tr>
		</tbody>
	</table>
	<div  id="paginate_casas" class="container_pagination">
		
	</div>
	<!-- Tempate TR table tomado con js para ser procesado en la tabla de arriba -->
	<template id="template_tbody_casas_content">
		<tr>
			<td class=" manage-column column-cb check-column">N</td>
			<td class=" manage-column column-cb check-column">ID</td> 
			<td class=" manage-column column-categories">titulo</td>
			<td></td>
			<td class="td_actions">
				<button onclick="view_item(this)" data="casas" class="button action ">Ver</button>
				<button onclick="delete_item(this)" data="casas" class="button action borrar">Borrar</button>
			</td>
		</tr>
	</template>
<?php }