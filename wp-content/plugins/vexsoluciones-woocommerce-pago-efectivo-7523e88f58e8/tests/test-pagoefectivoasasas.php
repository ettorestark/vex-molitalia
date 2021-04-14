<?php
/**
 * PagoEfectivo Gateway Pagoefectivo/asasas Tests.
 *
 * @since   1.0.0
 * @package PagoEfectivo_Gateway
 */
class PEG_Pagoefectivo/asasas_Test extends WP_UnitTestCase {

	/**
	 * Test if our class exists.
	 *
	 * @since  1.0.0
	 */
	function test_class_exists() {
		$this->assertTrue( class_exists( 'PEG_Pagoefectivo/asasas' ) );
	}

	/**
	 * Test that we can access our class through our helper function.
	 *
	 * @since  1.0.0
	 */
	function test_class_access() {
		$this->assertInstanceOf( 'PEG_Pagoefectivo/asasas', vex_pagoefectivo_gateway()->pagoefectivoasasas );
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
