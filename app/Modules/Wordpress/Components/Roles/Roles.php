<?php
namespace DevStudio\Modules\Wordpress\Components\Roles;

use DevStudio\Core\Abstracts\Component;
use DevStudio\Core\Abstracts\Unit;

/**
 * Wordpress.Roles component class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Roles extends Component {

	public $name = 'Roles';
    public $title = 'Roles';

	public function __construct(  ) {

		parent::__construct(  );

	}
}

/**
 * Wordpress.Roles -> Roles unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Roles extends Unit {

    public $name = 'Roles';
    public $title = 'Roles';

    public function data() {

        if (!empty($GLOBALS['wp_roles']) && is_object($GLOBALS['wp_roles'])) {
            foreach ($GLOBALS['wp_roles']->roles as $key => $value) {

                $_cols = [];
                foreach ($value['capabilities'] as $_key => $_val) {
                    $_cols[] = [
                        ['val' => $_key],
                        ['val' => $_val],
                    ];
                }

                $this->data[] = [
                    'class' => 'info',
                    'cols' => [
                        ['val' => $value['name']],
                        ['val' => $key]
                        /*,
                            [
                                'type' => 'data-info',
                                'val' => DevStudio()->template('data/simple-array', [
                                    'title' => __('Capabilities', 'dev-studio'),
                                    'rows' => $_cols
                                ])
                            ]
                        */
                    ],
                    'info' => DevStudio()->template('data/simple-array', [
                            'title' => __('Capabilities', 'dev-studio'),
                            'rows' => $_cols
                        ]
                    )
                ];
            }
        }

    }

    public function html() {

        return DevStudio()->template('data/table', [
            'rows' => $this->data
        ]);

    }

}

/**
 * Wordpress.WP_Roles -> Roles unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_WP_Roles extends Unit {

    public $name = 'WP_Roles';
    public $title = 'WP_Roles';

    public function encode_data($data) {
        return serialize($data);
    }

    public function decode_data($data) {
        return unserialize($data);
    }

    public function data() {

        if (!empty($GLOBALS['wp_roles']) && is_object($GLOBALS['wp_roles'])) {
            $this->data = $GLOBALS['wp_roles'];
        }

    }

    public function html() {
        return DevStudio()->template('data/object', $this->data);
    }
}