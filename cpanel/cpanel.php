<?php
require_once PLUGIN_DIR . 'cpanel/views/casas.php';
require_once PLUGIN_DIR . 'cpanel/views/forms_casas.php';
require_once PLUGIN_DIR . 'cpanel/views/tarifas.php';
require_once PLUGIN_DIR . 'cpanel/views/forms_tarifas.php';
require_once PLUGIN_DIR . 'cpanel/views/cargos_especiales.php';
require_once PLUGIN_DIR . 'cpanel/views/forms_cargos_especiales.php';
require_once PLUGIN_DIR . 'cpanel/views/reservas.php';
require_once PLUGIN_DIR . 'cpanel/views/forms_reservas.php';

function wp_tarifa_reserve_cpanel() {
	wp_enqueue_style( 'cpanel_css', PLUGIN_DIR_2 . 'cpanel/cpanel.css' );
	wp_register_script('js_cpanel', PLUGIN_DIR_2 . 'cpanel/cpanel.js', '1', true);
		wp_enqueue_script('js_cpanel');
		wp_localize_script( 'js_cpanel', 'wp_rest_api_cpanel', array(
			'root' => esc_url_raw( rest_url() ),
			'nonce' => wp_create_nonce( 'wp_rest' ),
			'home_url' => esc_url_raw( home_url() ),
		) );

	add_menu_page(
		__( 'Reservaciones', 'wp-tarifa-reserve' ),
		'Reservas',
		"edit_published_posts",
		'reservaciones',
		'funct_cpanel_ui',
		'',
		6
	);
}

function funct_cpanel_ui(){ ?>
	<div class="cpanel_body wrap" >
	
        <div class="header_wp_tarifa_reserve_cpanel nav-tab-wrapper">
            <nav>
				<button id="ventana_reservas" class="nav-tab" >Reservas</button>
                <button id="ventana_casas" class="nav-tab" >Casas</button>
                <button id="ventana_tarifas" class="nav-tab" >Tarifas</button>
                <button id="ventana_cargos_especiales" class="nav-tab" >Cargos especiales</button>
            </nav>
        </div>
        <section >

			<article id="ventana_reservas" >
				<h2>Tablero de reservas</h2>
				<hr>
				<?php
					forms_reservas();
					?>
					<hr>
				<?php 
					window_reservas();
				?>
			</article>		
			
            <article id="ventana_casas" >
				<h2>Añadir casas</h2>
				<hr>
				
				<?php 
					form_casas();
				?>
					<hr>
				<?php 
					window_casas();
				?>
			</article>

			<article id="ventana_tarifas">
				<h2>Añadir tarifas a casas por cantidad de huespedes</h2>
				<hr>
				
				<?php 
					form_tarifas();
					?>
					<hr>
				<?php 
					window_tarifa();
				?>
			</article>

			<article id="ventana_cargos_especiales" >
				<h2>Añadir cargos extras a dias expeciales</h2>
				<hr>
				<?php 
					form_cargos_especiales();
					?>
					<hr>
				<?php 
					window_cargos_especiales();
				?>
			</article>

        </section>
	</div>
<?php }
add_action( 'admin_menu', 'wp_tarifa_reserve_cpanel' );