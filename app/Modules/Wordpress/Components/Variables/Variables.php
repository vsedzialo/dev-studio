<?php
namespace DevStudio\Modules\Wordpress\Components\Variables;

use DevStudio\Core\Abstracts\Component;
use DevStudio\Core\Abstracts\Unit;
use DevStudio\Helpers\Utils;

/**
 * Wordpress.Variables component class
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

	public function __construct(  ) {

		parent::__construct();

	}
}

/**
 * Wordpress.Variables -> Browser unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Browser extends Unit {

    public $name = 'Browser';
    public $title = 'Browser';

    public function data() {

        $data = [
            ['iPhone Safari', '$is_iphone', Utils::true_false($GLOBALS['is_iphone'])],
            ['Google Chrome', '$is_chrome', Utils::true_false($GLOBALS['is_chrome'])],
            ['Safari', '$is_safari', Utils::true_false($GLOBALS['is_safari'])],
            ['Netscape 4', '$is_NS4', Utils::true_false($GLOBALS['is_NS4'])],
            ['Opera', '$is_opera', Utils::true_false($GLOBALS['is_opera'])],
            ['Mac Internet Explorer', '$is_macIE', Utils::true_false($GLOBALS['is_macIE'])],
            ['Windows Internet Explorer', '$is_winIE', Utils::true_false($GLOBALS['is_winIE'])],
            ['FireFox', '$is_gecko', Utils::true_false($GLOBALS['is_gecko'])],
            ['Lynx', '$is_lynx', Utils::true_false($GLOBALS['is_lynx'])],
            ['Internet Explorer', '$is_IE', Utils::true_false($GLOBALS['is_IE'])],
            ['Microsoft Edge', '$is_edge', Utils::true_false($GLOBALS['is_edge'])]
        ];

        foreach ($data as $key => $val) {
            $this->data[] = [
                'cols' => [
                    ['val' => $val[0], 'style' => 'width:25%'],
                    ['val' => $val[1], 'style' => 'width:25%', 'class' => 'ds-php-style'],
                    ['val' => $val[2], 'style' => 'width:50%'],
                ]
            ];
        }
    }

    public function html() {

        return DevStudio()->template('data/table', [
            'rows' => $this->data
        ]);

    }

}

/**
 * Wordpress.Variables -> Server unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Server extends Unit {

    public $name = 'Server';
    public $title = 'Server';

    public function data() {

        $data = [
            ['Apache HTTP Server', '$is_apache', Utils::true_false($GLOBALS['is_apache'])],
            ['Microsoft Internet Information Services (IIS)', '$is_IIS', Utils::true_false($GLOBALS['is_IIS'])],
            ['Microsoft Internet Information Services (IIS) v7.x', '$is_iis7', Utils::true_false($GLOBALS['is_iis7'])],
            ['Nginx Web Server', '$is_nginx', Utils::true_false($GLOBALS['is_nginx'])],
        ];

        foreach ($data as $key => $val) {
            $this->data[] = [
                'cols' => [
                    ['val' => $val[0], 'style' => 'width:30%', 'attrs' => ['noWrap']],
                    ['val' => $val[1], 'style' => 'width:15%', 'class' => 'ds-php-style'],
                    ['val' => $val[2], 'style' => 'width:55%'],
                ]
            ];
        }
    }

    public function html() {

        return DevStudio()->template('data/table', [
            'rows' => $this->data
        ]);

    }

}