<?php
namespace DevStudio\Core\Abstracts;

/**
 * Module abstract class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
abstract class Module {

	public $name;

	public $title;

	public $components = [];

    /**
     * Module constructor.
     *
     * @since 1.0.0
     */
	public function __construct( ) {
        if (empty($this->title)) {
            $this->set_title($this->title);
        }
	}

    /**
     * Set module title
     *
     * @since 1.0.0
     * @param $title
     */
	public function set_title( $title ) {
		$this->title = function_exists( '__' ) ? __( $title, 'dev-studio' ) : $title;
	}

    /**
     * Add component to module
     *
     * @since 1.0.0
     * @param Component $component
     */
	public function add_component( Component $component  ) {
		$this->components[ $component->name ] = $component;
	}

    /**
     * Get module's components
     *
     * @since 1.0.0
     * @return array
     */
	public function components() {
		return $this->components;
	}
}