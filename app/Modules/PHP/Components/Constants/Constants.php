<?php
namespace DevStudio\Modules\PHP\Components\Constants;

use DevStudio\Core\Abstracts\Component;
use DevStudio\Core\Abstracts\Unit;
use DevStudio\Helpers\Utils;

/**
 * PHP.Constants component class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Constants extends Component {

	public $name = 'Constants';
    public $title = 'Constants';

	public function __construct(  ) {

		parent::__construct(  );

	}
}

/**
 * PHP.Constants -> Constants unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Constants extends Unit {

    public $name = 'Constants';
    public $title = 'Constants';

    public function data() {

        $constants = get_defined_constants(true);
        unset($constants['user']);

        foreach ($constants as $category => $data) {
            $this->data[] = [
                'type' => 'title',
                'val' => ucfirst($category)
            ];

            foreach ($data as $key => $value) {
                $this->data[] = [
                    'cols' => [
                        ['val' => $key],
                        ['val' => Utils::get_const_value($key)]
                    ]
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