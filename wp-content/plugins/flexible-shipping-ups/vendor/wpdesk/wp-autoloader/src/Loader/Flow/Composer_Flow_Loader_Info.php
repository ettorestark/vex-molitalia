<?php

/**
 * Info required for Composer_Flow_Loader
 */
class WPDesk_Composer_Flow_Loader_Info {
	/** @var int */
	private $load_priority;

	/** @var \SplFileInfo */
	private $autoload_file;

	/** @var WPDesk_Plugin_Build_Flow */
	private $build_flow;

	/** @var WPDesk_Plugin_Info */
	private $plugin_info;

	/**
	 * @param int $load_priority
	 */
	public function set_load_priority( $load_priority ) {
		$this->load_priority = $load_priority;
	}

	/**
	 * @param SplFileInfo $autoload_file
	 */
	public function set_autoload_file( $autoload_file ) {
		$this->autoload_file = $autoload_file;
	}

	/**
	 * @param WPDesk_Plugin_Build_Flow $build_flow
	 */
	public function set_build_flow( $build_flow ) {
		$this->build_flow = $build_flow;
	}

	/**
	 * @return int
	 */
	public function get_load_priority() {
		return $this->load_priority;
	}

	/**
	 * @return SplFileInfo
	 */
	public function get_autoload_file() {
		return $this->autoload_file;
	}

	/**
	 * @return WPDesk_Plugin_Build_Flow
	 */
	public function get_build_flow() {
		return $this->build_flow;
	}

	/**
	 * @return WPDesk_Plugin_Info
	 */
	public function get_plugin_info() {
		return $this->plugin_info;
	}

	/**
	 * @param WPDesk_Plugin_Info $plugin_info
	 */
	public function set_plugin_info( $plugin_info ) {
		$this->plugin_info = $plugin_info;
	}

}