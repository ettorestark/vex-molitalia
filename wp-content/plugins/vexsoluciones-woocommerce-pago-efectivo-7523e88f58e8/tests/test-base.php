<?php
/**
 * Vexsoluciones_Gateway_Pagoefectivo.
 *
 * @since   1.0.0
 * @package Vexsoluciones_Gateway_Pagoefectivo
 */
class Vexsoluciones_Gateway_Pagoefectivo_Test extends WP_UnitTestCase {

	/**
	 * Test if our class exists.
	 *
	 * @since  1.0.0
	 */
	function test_class_exists() {
		$this->assertTrue( class_exists( 'Vexsoluciones_Gateway_Pagoefectivo') );
	}

	/**
	 * Test that our main helper function is an instance of our class.
	 *
	 * @since  1.0.0
	 */
	function test_get_instance() {
		$this->assertInstanceOf(  'Vexsoluciones_Gateway_Pagoefectivo', vexsoluciones_gateway_pagoefectivo() );
	}

	/**
	 * Replace this with some actual testing code.
	 *
	 * @since  1.0.0
	 */
	function test_sample() {
		$this->assertTrue( true );
	}
}
