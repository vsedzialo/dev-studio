<?php
namespace DevStudio\Modules\Wordpress\Components\Locale;

use DevStudio\Core\Abstracts\Component;
use DevStudio\Core\Abstracts\Unit;
use DevStudio\Helpers\Utils;

/**
 * Wordpress.Locale component class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Locale extends Component {

	public $name = 'Locale';
    public $title = 'Locale';

	public function __construct(  ) {

		parent::__construct(  );

	}
}

/**
 * Wordpress.Locale -> WP_Locale unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_WP_Locale extends Unit {

    public $name = 'WP_Locale';
    public $title = 'WP_Locale';

    public function encode_data($data) {
        return serialize($data);
    }

    public function decode_data($data) {
        return unserialize($data);
    }

    public function data() {

        if (isset($GLOBALS['wp_locale'])) {
            $this->data = $GLOBALS['wp_locale'];
        } else {
            $this->data = [
                'message' => Utils::not_available()
            ];
        }
    }

    public function html() {

        return DevStudio()->template('data/object', $this->data);

    }

}