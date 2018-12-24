<?php
namespace DevStudio\Modules;

/**
 * MySQL module class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class MySQL extends \DevStudio\Core\Abstracts\Module {

	public $name = 'MySQL';
	public $title = 'MySQL';

	public function __construct() {

		parent::__construct();

	}
}