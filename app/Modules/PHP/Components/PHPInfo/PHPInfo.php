<?php
namespace DevStudio\Modules\PHP\Components\PHPInfo;

use DevStudio\Core\Abstracts\Component;
use DevStudio\Core\Abstracts\Unit;
use DevStudio\Helpers\Utils;

/**
 * PHP.PHPInfo component class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */

class PHPInfo extends Component {

    public $name = 'PHPInfo';
    public $title = 'PHPInfo';

    public function __construct() {

        parent::__construct();

    }
}

/**
 * PHP.PHPInfo -> PHPInfo unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_PHPInfo extends Unit {

    public $name = 'PHPInfo';
    public $title = 'PHPInfo';
    public $type = 'static';

    public function data() {
    }

    public function html() {
        ob_start();
        echo '<div class="ds-phpinfo">';
        phpinfo();
        echo '</div>';
        return ob_get_clean();
    }

}

