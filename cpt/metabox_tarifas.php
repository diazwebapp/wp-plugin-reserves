<?php 
add_action( 'add_meta_boxes', 'register_meta_box_tarifa' );
function register_meta_box_tarifa(){
	add_meta_box(
		'meta_tarifa',
		'Datos tarifa',
		'func_tarifa',
		'tarifas',
		'normal',
		'high'
	);
}

function func_tarifa($post){ 
	$min_huespedes = $post->min_huespedes;
	$max_huespedes = $post->max_huespedes;
	//Días de la semana
	$day_0 = $post->day_0;
	$day_1 = $post->day_1;
	$day_2 = $post->day_2;
	$day_3 = $post->day_3;
	$day_4 = $post->day_4;
	$day_5 = $post->day_5;
	$day_6 = $post->day_6;
    wp_nonce_field( 'tarifa_nonce', 'tarifa_nonce' ); ?>
  
	<div>
		<article>
			<label for="">huespedes minimos</label>
			<input type="number" name="min_huespedes" id="min_huespedes" required value="<?php echo $min_huespedes ?>" >
		</article>
		<article>
			<label for="">Huespedes maximos</label>
			<input type="number" name="max_huespedes" id="max_huespedes" required value="<?php echo $max_huespedes ?>">
		</article>

		<!-- Dias de la semana-->
		
		<article>
			<label for="">Domingo</label>
			<input type="number" name="day_0" id="day_0" required value="<?php echo $day_0 ?>">
		</article>
		<article>
			<label for="">Lunes</label>
			<input type="number" name="day_1" id="day_1" required value="<?php echo $day_1 ?>">
		</article>
		<article>
			<label for="">Martes</label>
			<input type="number" name="day_2" id="day_2" required value="<?php echo $day_2 ?>">
		</article>
		<article>
			<label for="">Miercoles</label>
			<input type="number" name="day_3" id="day_3" required value="<?php echo $day_3 ?>">
		</article>
		<article>
			<label for="">Jueves</label>
			<input type="number" name="day_4" id="day_4" required value="<?php echo $day_4 ?>">
		</article>
		<article>
			<label for="">Viernes</label>
			<input type="number" name="day_5" id="day_5" required value="<?php echo $day_5 ?>">
		</article>
		<article>
			<label for="">Sabado</label>
			<input type="number" name="day_6" id="day_6" required value="<?php echo $day_6 ?>">
		</article> 
	</div>
<?php } 

//guardando datos de los meta
/**
 * Graba los campos personalizados que vienen del formulario de edición del post
 *
 */
function save_meta_boxes( $post_id ) {
	// Comprueba que el tipo de post es tarifa.
	if ( isset( $_POST ) && 'tarifas' !== $_POST['post_type'] ) {
		return $post_id;
	}
    // Comprueba que el nonce es correcto para evitar ataques CSRF.
	if ( ! isset( $_POST['tarifa_nonce'] ) || ! wp_verify_nonce( $_POST['tarifa_nonce'], 'tarifa_nonce' ) ) {
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

	$day_0 = sanitize_post( $_POST['day_0'] );
	update_post_meta( $post_id, 'day_0', $day_0 );

	$day_1 = sanitize_post( $_POST['day_1'] );
	update_post_meta( $post_id, 'day_1', $day_1 );

	$day_2 = sanitize_post( $_POST['day_2'] );
	update_post_meta( $post_id, 'day_2', $day_2 );

	$day_3 = sanitize_post( $_POST['day_3'] );
	update_post_meta( $post_id, 'day_3', $day_3 );

	$day_4 = sanitize_post( $_POST['day_4'] );
	update_post_meta( $post_id, 'day_4', $day_4 );

	$day_5 = sanitize_post( $_POST['day_5'] );
	update_post_meta( $post_id, 'day_5', $day_5 );

	$day_6 = sanitize_post( $_POST['day_6'] );
	update_post_meta( $post_id, 'day_6', $day_6 );
    return true;
}
add_action( 'save_post', 'save_meta_boxes' );