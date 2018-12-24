<?php
namespace DevStudio\Modules\Wordpress\Components\Actions;

use DevStudio\Core\Abstracts\Component;
use DevStudio\Core\Abstracts\Unit;

/**
 * Wordpress.Actions component class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Actions extends Component {

	public $name = 'Actions';
    public $title = 'Actions';

	public function __construct(  ) {

		parent::__construct(  );

	}
}

/**
 * Wordpress.Actions -> Actions unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Actions extends Unit {

    public $name = 'Actions';
    public $title = 'Actions';

    public function data() {
        global $wp_actions;

        if (isset($wp_actions) && !empty($wp_actions)) {
            foreach ($wp_actions as $action => $fires) {

                // Exclude me
                if (DevStudio()->exclude_me() && DevStudio()->me('action', $action)) continue;

                $this->data[$action] = $fires;
            }
        }
    }

    public function html() {
        $rows = [];
        foreach ($this->data as $key => $value) {
            $rows[] = [
                'cols' => [
                    ['class' => 'ds-pos'],
                    ['val' => $key, 'style'=>'width:350px'],
                    ['val' => $value]
                ]
            ];
        }

        return DevStudio()->template('data/table', [
            'order' => [
                [],
                ['show' => true],
                ['show' => true, 'type' => 'number']
            ],
            'headers' => [
                ['title' => '#'],
                ['title' => __('Action', 'dev-studio')],
                ['title' => __('Fires', 'dev-studio')],
            ],
            'rows' => $rows
        ]);
    }

}