<?php
namespace DevStudio\Modules;

/**
 * WooCommerce module class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class WooCommerce extends \DevStudio\Core\Abstracts\Module {

	public $name = 'WooCommerce';
	public $title = 'WooCommerce';

	public function __construct() {

		parent::__construct();

	}
}