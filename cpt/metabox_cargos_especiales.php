<?php 
add_action( 'add_meta_boxes', 'register_meta_box_cargos_especiales' );
function register_meta_box_cargos_especiales(){
	add_meta_box(
		'meta_cargos_especiales',
		'Datos cargos_especiales',
		'func_cargos_especiales',
		'cargos_especiales',
		'normal',
		'high'
	);
}


function func_cargos_especiales($post){ 
	$fecha_especial = $post->fecha_especial;
	$asunto = $post->asunto;
	$monto = $post->monto;
    wp_nonce_field( 'cargos_especiales_nonce', 'cargos_especiales_nonce' ); ?>
  
  <div>
		<article>
			<label for="">Asunto</label>
			<input type="text" name="asunto" id="asunto" required value="<?php echo $asunto ?>" >
		</article>
		
		<article>
			<label for="">Fecha</label>
			<input type="date" name="fecha_especial" id="fecha_especial" required value="<?php echo $fecha_especial ?>">
		</article>

		<article>
			<label for="">Monto</label>
			<input type="number" name="monto" step="0.01" id="monto" required value="<?php echo $monto ?>">
		</article>
		
	</div>
<?php } 

//guardando datos de los meta
/**
 * Graba los campos personalizados que vienen del formulario de edición del post
 *
 */
function save_meta_cargos_especiales( $post_id ) {
	// Comprueba que el tipo de post es cargo._especiales
	if ( isset( $_POST ) && 'cargos_especiales' !== $_POST['post_type'] ) {
		return $post_id;
	}
    // Comprueba que el nonce es correcto para evitar ataques CSRF.
	if ( ! isset( $_POST['cargos_especiales_nonce'] ) || ! wp_verify_nonce( $_POST['cargos_especiales_nonce'], 'cargos_especiales_nonce' ) ) {
		return $post_id;
	}
	// Comprueba que el usuario actual tiene permiso para editar esto
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		wp_die(
			'<h1>' . __( 'Necesitas más privilegios para publicar contenidos.', 'apuestanweb-lang' ) . '</h1>' .
			'<p>' . __( 'Lo siento, no puedes crear contenidos desde esta cuenta.', 'apuestanweb-lang' ) . '</p>',
			403
		);
	}
	// Ahora puedes grabar los datos

    // 1- minimo de huespedes
	$min_huespedes = sanitize_post( $_POST['min_huespedes'] );
	update_post_meta( $post_id, 'min_huespedes', $min_huespedes );
 	// 2- miximo de huespedes
    $max_huespedes = sanitize_post( $_POST['max_huespedes'] );
	update_post_meta( $post_id, 'max_huespedes', $max_huespedes );
	
	//Dias de la semana

	$fecha_especial = sanitize_post( $_POST['fecha_especial'] );
	update_post_meta( $post_id, 'fecha_especial', $fecha_especial );

	$asunto = sanitize_post( $_POST['asunto'] );
	update_post_meta( $post_id, 'asunto', $asunto );

	$monto = sanitize_post( $_POST['monto'] );
	update_post_meta( $post_id, 'monto', $monto );

    return true;
}
add_action( 'save_post', 'save_meta_cargos_especiales' );