<?php
/** 
 * Configuración básica de WordPress.
 *
 * Este archivo contiene las siguientes configuraciones: ajustes de MySQL, prefijo de tablas,
 * claves secretas, idioma de WordPress y ABSPATH. Para obtener más información,
 * visita la página del Codex{@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} . Los ajustes de MySQL te los proporcionará tu proveedor de alojamiento web.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** Ajustes de MySQL. Solicita estos datos a tu proveedor de alojamiento web. ** //
/** El nombre de tu base de datos de WordPress */
define('DB_NAME', 'molitalia');

/** Tu nombre de usuario de MySQL */
define('DB_USER', 'molitalia');

/** Tu contraseña de MySQL */
define('DB_PASSWORD', 'Yq+Jhjw8MPpZ7Xyn');

/** Host de MySQL (es muy probable que no necesites cambiarlo) */
define('DB_HOST', 'localhost');

/** Codificación de caracteres para la base de datos. */
define('DB_CHARSET', 'utf8');

/** Cotejamiento de la base de datos. No lo modifiques si tienes dudas. */
define('DB_COLLATE', '');

/**#@+
 * Claves únicas de autentificación.
 *
 * Define cada clave secreta con una frase aleatoria distinta.
 * Puedes generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress}
 * Puedes cambiar las claves en cualquier momento para invalidar todas las cookies existentes. Esto forzará a todos los usuarios a volver a hacer login.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '/]W0k%/J)vUDop!?&qxcF(sw,Q%^sCg-E|J9 6DPB$%c. 4G8Iq52ehQAqp)F(AE');
define('SECURE_AUTH_KEY',  '7|rK1K6XK34X--VR]>?E0eg|+eg{MR!qQ0$*C7@3ifkNtP+@l$w1GDai ~%u[-%6');
define('LOGGED_IN_KEY',    'g`+t=~v#ZL6/Dg_v$(EyZa$^qBa|9i2^iBo1|F2-+9ysy |^@%xd5UZOCdk/a*`s');
define('NONCE_KEY',        'coEqS({Q^-Q9hAcBWT~LlY]c0|bmCrpnEK`E_-Vm,jx(bnuK-;Ox&20a+n[s2FSg');
define('AUTH_SALT',        '!#qAy!05)s%!zI&N-;-Yf9tTQO{@:g1+t?:_x^4O8adLL*jBPF4C BAh,m0P*5s4');
define('SECURE_AUTH_SALT', '=5k f4DpL9+*?dr$,-l&=EOvvg{#&X!p!||Z56.=JQuSgj)dFLTO=f#8l)|&:[5A');
define('LOGGED_IN_SALT',   '}lmh{U7hghu}wp[N$-=*f]6kMR]A&wt~7R5W>::ku]zc[R8,f[3Ww~8Z,g6mDVj>');
define('NONCE_SALT',       'tX+Wr.3rXZcij;[d1m)FR^fLBP(j>u|LA6tD~sSG2FcM#&eutu[N(`oF|p>&*>@Z');

/**#@-*/

/**
 * Prefijo de la base de datos de WordPress.
 *
 * Cambia el prefijo si deseas instalar multiples blogs en una sola base de datos.
 * Emplea solo números, letras y guión bajo.
 */
$table_prefix  = 'wp_';


/**
 * Para desarrolladores: modo debug de WordPress.
 *
 * Cambia esto a true para activar la muestra de avisos durante el desarrollo.
 * Se recomienda encarecidamente a los desarrolladores de temas y plugins que usen WP_DEBUG
 * en sus entornos de desarrollo.
 */
define('WP_DEBUG', false);

define('FS_METHOD', 'direct');

/* ¡Eso es todo, deja de editar! Feliz blogging */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
