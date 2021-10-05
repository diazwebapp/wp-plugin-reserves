<?php 
add_action( 'add_meta_boxes', 'register_meta_box' );
function register_meta_box(){
	add_meta_box(
		'meta_reservas',
		'Datos reservas',
		'funct_meta_reservas',
		'reservas',
		'normal',
		'high'
	);
}


function funct_meta_reservas($post){ 
	$titulo_inmueble = $post->titulo_inmueble;
	$checkin = $post->checkin;
	$checkout = $post->checkout;
	$cliente = $post->cliente;
	$telefono = $post->telefono; 
	$correo = $post->correo;
	$monto = $post->monto;
	$observaciones = $post->observaciones;
    wp_nonce_field( 'reservas_nonce', 'reservas_nonce' ); ?>
  
	<div>
		<article>
			<label for="">titulo_inmueble</label>
			<input type="text" name="titulo_inmueble" id="titulo_inmueble" required value="<?php echo $titulo_inmueble ?>">
		</article>
		<article>
			<label for="">checkin</label>
			<input type="text" name="checkin" id="checkin" required value="<?php echo $checkin ?>">
		</article>
		<article>
			<label for="">checkout</label>
			<input type="text" name="checkout" id="checkout" required value="<?php echo $checkout ?>">
		</article>
		<article>
			<label for="">cliente</label>
			<input type="text" name="cliente" id="cliente" required value="<?php echo $cliente ?>">
		</article>
		<article>
			<label for="">telefono</label>
			<input type="text" name="telefono" id="telefono" required value="<?php echo $telefono ?>">
		</article>
		<article>
			<label for="">correo</label>
			<input type="email" name="correo" id="correo" required value="<?php echo $correo ?>">
		</article>
		<article>
			<label for="">monto</label>
			<input type="number" name="monto" id="monto" required value="<?php echo intval($monto) ?>">
		</article>
		<article>
			<label for="">observaciones</label>
			<textarea name="observaciones" id="observaciones" ><?php echo $observaciones ?></textarea>
		</article>
	</div>
<?php } 

//guardando datos de los meta
/**
 * Graba los campos personalizados que vienen del formulario de edición del post
 *
 */
function ca_save_meta_boxes( $post_id ) {
	// Comprueba que el tipo de post es reserva.
	if ( isset( $_POST ) && 'reservas' !== $_POST['post_type'] ) {
		return $post_id;
	}
    // Comprueba que el nonce es correcto para evitar ataques CSRF.
	if ( ! isset( $_POST['reservas_nonce'] ) || ! wp_verify_nonce( $_POST['reservas_nonce'], 'reservas_nonce' ) ) {
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

    
	if(isset($_POST['titulo_inmueble']) && $_POST['titulo_inmueble'] !== ''): update_post_meta( $new_post_id, 'titulo_inmueble', sanitize_post( $_POST['titulo_inmueble'] ) ); endif;

	if(isset($_POST['checkin']) && $_POST['checkin'] !== ''): update_post_meta( $new_post_id, 'checkin', sanitize_post( $_POST['checkin'] ) ); endif;

	if(isset($_POST['checkout']) && $_POST['checkout'] !== ''): update_post_meta( $new_post_id, 'checkout', sanitize_post( $_POST['checkout'] ) ); endif;

	if(isset($_POST['huespedes']) && $_POST['huespedes'] !== ''): update_post_meta( $new_post_id, 'huespedes', sanitize_post( $_POST['huespedes'] ) ); endif;

	if(isset($_POST['cliente']) && $_POST['cliente'] != ''): update_post_meta( $new_post_id, 'cliente', sanitize_post( $_POST['cliente'] ) ); endif;

    if(isset($_POST['tefefono']) && $_POST['tefefono'] != ''): update_post_meta( $new_post_id, 'tefefono', sanitize_post( $_POST['tefefono'] ) ); endif;

	if(isset($_POST['correo']) && $_POST['correo'] == ''): update_post_meta( $new_post_id, 'correo', sanitize_post( $_POST['correo'] ) ); endif;

	if(isset($_POST['monto']) && $_POST['monto'] != ''): update_post_meta( $new_post_id, 'monto', sanitize_post( $_POST['monto'] ) ); endif;

	if(isset($_POST['observaciones']) && $_POST['observaciones'] != ''): update_post_meta( $new_post_id, 'observaciones', sanitize_post( $_POST['observaciones'] ) ); endif;

    return true;
}
add_action( 'save_post', 'ca_save_meta_boxes' );