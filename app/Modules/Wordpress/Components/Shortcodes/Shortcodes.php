<?php
namespace DevStudio\Modules\Wordpress\Components\Shortcodes;

use DevStudio\Core\Abstracts\Component;
use DevStudio\Core\Abstracts\Unit;

/**
 * Wordpress.Shortcodes component class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Shortcodes extends Component {

	public $name = 'Shortcodes';
    public $title = 'Shortcodes';

	public function __construct(  ) {

		parent::__construct(  );

	}
}

/**
 * Wordpress.Shortcodes -> Shortcodes unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Shortcodes extends Unit {

    public $name = 'Shortcodes';
    public $title = 'Shortcodes';

    public function data() {
        global $shortcode_tags;

        if (!empty($shortcode_tags)) {
            foreach ($shortcode_tags as $shortcode => $callback) {
                if (is_array($callback) && is_object($callback[0]) && isset($callback[1])) {
                    $callback = get_class($callback[0]).'::'.$callback[1];
                }
                $this->data[] = [
                    'cols' => [
                        ['val' => $shortcode],
                        ['val' => $callback]
                    ]
                ];
            }
        }
    }

    public function html() {

        return DevStudio()->template('data/table', [
            'headers' => [
                ['title' => __('Shortcode', 'dev-studio')],
                ['title' => __('Callback', 'dev-studio')],
            ],
            'rows' => $this->data
        ]);
    }

}