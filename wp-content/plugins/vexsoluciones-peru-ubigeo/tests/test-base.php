<?php
/**
 * Vexsoluciones_Peru_Ubigeo.
 *
 * @since   1.0
 * @package Vexsoluciones_Peru_Ubigeo
 */
class Vexsoluciones_Peru_Ubigeo_Test extends WP_UnitTestCase {

	/**
	 * Test if our class exists.
	 *
	 * @since  1.0
	 */
	function test_class_exists() {
		$this->assertTrue( class_exists( 'Vexsoluciones_Peru_Ubigeo') );
	}

	/**
	 * Test that our main helper function is an instance of our class.
	 *
	 * @since  1.0
	 */
	function test_get_instance() {
		$this->assertInstanceOf(  'Vexsoluciones_Peru_Ubigeo', vexsoluciones_peru_ubigeo() );
	}

	/**
	 * Replace this with some actual testing code.
	 *
	 * @since  1.0
	 */
	function test_sample() {
		$this->assertTrue( true );
	}
}
