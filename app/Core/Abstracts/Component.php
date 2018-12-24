<?php
namespace DevStudio\Core\Abstracts;

/**
 * Component abstract class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
abstract class Component
{
	public $name;

	public $title;

    public $units = [];

    /**
     * Component constructor.
     *
     * @since 1.0.0
     */
    public function __construct() {

        if (empty($this->title)) {
            $this->set_title($this->title);
        }
    }

    /**
     * Set component title
     *
     * @since 1.0.0
     * @param $title
     */
	public function set_title( $title ) {
		$this->title = function_exists( '__' ) ? __( $title, 'dev-studio' ) : $title;
	}

    /**
     * Add unit to component
     *
     * @since 1.0.0
     * @param Unit $unit
     */
	public function add_unit( Unit $unit ) {
		$this->units[ $unit->name ] = $unit;
	}

    /**
     * Get component's units
     *
     * @since 1.0.0
     * @return array
     */
	public function units() {
		return $this->units;
	}
}