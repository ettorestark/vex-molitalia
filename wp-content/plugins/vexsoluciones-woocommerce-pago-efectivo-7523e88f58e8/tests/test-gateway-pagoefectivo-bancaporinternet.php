<?php
/**
 * PagoEfectivo Gateway Gateway Pagoefectivo Bancaporinternet Tests.
 *
 * @since   1.0.0
 * @package PagoEfectivo_Gateway
 */
class PEG_Gateway_Pagoefectivo_Bancaporinternet_Test extends WP_UnitTestCase {

	/**
	 * Test if our class exists.
	 *
	 * @since  1.0.0
	 */
	function test_class_exists() {
		$this->assertTrue( class_exists( 'PEG_Gateway_Pagoefectivo_Bancaporinternet' ) );
	}

	/**
	 * Test that we can access our class through our helper function.
	 *
	 * @since  1.0.0
	 */
	function test_class_access() {
		$this->assertInstanceOf( 'PEG_Gateway_Pagoefectivo_Bancaporinternet', vex_pagoefectivo_gateway()->gateway_pagoefectivo_bancaporinternet );
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
