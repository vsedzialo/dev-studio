<?php
namespace DevStudio\Modules\Wordpress\Components\Rewrite;

use DevStudio\Core\Abstracts\Component;
use DevStudio\Core\Abstracts\Unit;
use DevStudio\Helpers\Utils;

/**
 * Wordpress.Rewrite component class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Rewrite extends Component {

	public $name = 'Rewrite';
    public $title = 'Rewrite';

	public function __construct(  ) {

		parent::__construct(  );

	}
}

/**
 * Wordpress.Rewrite -> Rules unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Rules extends Unit {

    public $name = 'Rules';
    public $title = 'Rules';

    public function data() {

        if (!empty($GLOBALS['wp_rewrite']) && is_object($GLOBALS['wp_rewrite']) && isset($GLOBALS['wp_rewrite']->rules)) {
            $this->data = Utils::get_simple_data($GLOBALS['wp_rewrite']->rules);
        }
    }

    public function html() {

        return DevStudio()->template('data/table', [
            'rows' => $this->data
        ]);

    }
}

/**
 * Wordpress.Rewrite -> WP_Rewrite unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_WP_Rewrite extends Unit {

    public $name = 'WP_Rewrite';
    public $title = 'WP_Rewrite';

    public function encode_data($data) {
        return serialize($data);
    }

    public function decode_data($data) {
        return unserialize($data);
    }

    public function data() {

        if (!empty($GLOBALS['wp_rewrite']) && is_object($GLOBALS['wp_rewrite'])) {
            $this->data = $GLOBALS['wp_rewrite'];
        }
    }

    public function html() {

        return DevStudio()->template('data/object', $this->data);

    }
}