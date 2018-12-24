<?php
namespace DevStudio\Modules;

/**
 * Wordpress module class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Wordpress extends \DevStudio\Core\Abstracts\Module {

	public $name = 'Wordpress';
	public $title = 'WordPress';

	public function __construct() {

		parent::__construct();

	}
}