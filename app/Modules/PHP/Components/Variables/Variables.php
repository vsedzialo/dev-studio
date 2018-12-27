<?php
namespace DevStudio\Modules\PHP\Components\Variables;

use DevStudio\Core\Abstracts\Component;
use DevStudio\Core\Abstracts\Unit;
use DevStudio\Helpers\Utils;

/**
 * PHP.Variables component class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Variables extends Component {

    public $name = 'Variables';
    public $title = 'Variables';

    public function __construct() {

        parent::__construct();

    }
}

/**
 * PHP.Variables -> Cookie unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Cookie extends Unit {

    public $name = 'Cookie';
    public $title = 'COOKIE';

    public function data() {

        if (!empty($_COOKIE)) {
            $this->data = Utils::get_simple_data($_COOKIE);
        }

    }

    public function html() {

        return DevStudio()->template('data/table', [
            'rows' => $this->data
        ]);

    }
}

/**
 * PHP.Variables -> Get unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Get extends Unit {

    public $name = 'Get';
    public $title = 'GET';

    public function data() {

        if (!empty($GLOBALS['_GET'])) {
            $this->data = Utils::get_simple_data($GLOBALS['_GET']);
        }

    }

    public function html() {

        return DevStudio()->template('data/table', [
            'rows' => $this->data
        ]);

    }
}

/**
 * PHP.Variables -> Post unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Post extends Unit {

    public $name = 'Post';
    public $title = 'POST';

    public function data() {

        if (!empty($GLOBALS['_POST'])) {
            $this->data = Utils::get_simple_data($GLOBALS['_POST']);
        }
    }

    public function html() {

        return DevStudio()->template('data/table', [
            'rows' => $this->data
        ]);

    }
}

/**
 * PHP.Variables -> Server unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Server extends Unit {

    public $name = 'Server';
    public $title = 'SERVER';

    public function data() {

        if (!empty($GLOBALS['_SERVER'])) {
            $this->data = Utils::get_simple_data($GLOBALS['_SERVER']);
        }

    }

    public function html() {

        return DevStudio()->template('data/table', [
            'rows' => $this->data
        ]);

    }
}

/**
 * PHP.Variables -> Session unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Session extends Unit {

    public $name = 'Session';
    public $title = 'SESSION';

    public function data() {

        if (!empty($GLOBALS['_SESSION'])) {
            $this->data = Utils::get_simple_data($GLOBALS['_SESSION']);
        }

    }

    public function html() {

        return DevStudio()->template('data/table', [
            'rows' => $this->data
        ]);

    }
}

/**
 * PHP.Variables -> Files unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Files extends Unit {

    public $name = 'Files';
    public $title = 'FILES';

    public function data() {

        if (!empty($GLOBALS['_FILES'])) {
            $this->data = Utils::get_simple_data($GLOBALS['_FILES']);
        }
    }

    public function html() {

        return DevStudio()->template('data/table', [
            'rows' => $this->data
        ]);

    }
}

/**
 * PHP.Variables -> Env unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Env extends Unit {

    public $name = 'Env';
    public $title = 'ENV';

    public function data() {

        if (!empty($GLOBALS['_ENV'])) {
            $this->data = Utils::get_simple_data($GLOBALS['_ENV']);
        }

    }

    public function html() {

        return DevStudio()->template('data/table', [
            'rows' => $this->data
        ]);

    }
}


/**
 * PHP.Variables -> Globals unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Globals extends Unit {

    public $name = 'Globals';
    public $title = 'GLOBALS';

    public function data() {

        $len = 1000;
        foreach($GLOBALS as $key=>$value) {
            if (!in_array($key, ['_GET','_POST','_COOKIE','_FILES','_SERVER','_Variables','_ENV','GLOBALS'])) {
                $type = '';
                $val = $value;

                /*
                if (is_array($value) || is_object($value)) {
                    $type = is_array($value) ? 'array':'object';
                    $size = mb_strlen(print_r($value, true), 'UTF-8');

                    $val = '<div class="title">'.$key.'</div><pre class="'.$type.'">';
                    if ($size < $len) {
                        $val .= htmlspecialchars(print_r($value, true)).'</pre>';
                    } else {
                        $val .= htmlspecialchars(mb_substr(print_r($value, true), 0, $len, 'UTF-8')).'</pre>';
                        $val .= '<hr/>'.sprintf( _x('Limited to %1$d characters', 'dev-studio' ), $len);
                    }
                }
                */
                if (is_array($value) || is_object($value)) {
                    $val = is_array($value) ? '[ Array ]':'[ Object ]';
                    $this->data[$key] = [ 'val' => $val, 'class' => 'mask' ];
                } else {
                    $this->data[$key] = [ 'val' => $val ];
                }
            }
        }
        ksort($this->data);
    }

    public function html() {

        $rows = [];
        foreach ($this->data as $key => $item) {
            if (!isset($item['type'])) {
                $rows[] = [
                    'cols' => [
                        ['val' => $key],
                        $item
                    ]
                ];
            } else {
                $rows[] = [
                    'cols' => [
                        ['val' => $key],
                        ['val' => $item['val'], 'type' => 'data-info', 'text' => '[ '.ucfirst($item['type']).' ]'],
                    ],

                ];
            }
        }

        return DevStudio()->template('data/table', [
            'class' => 'info',
            'rows' => $rows
        ]);

    }

}