<?php
namespace DevStudio\Modules\MySQL\Components\Overview;

use DevStudio\Core\Abstracts\Component;
use DevStudio\Core\Abstracts\Unit;
use DevStudio\Data\MySQL;
use DevStudio\Helpers\Utils;

/**
 * MySQL.Overview component class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Overview extends Component {

	public $name = 'Overview';
    public $title = 'Overview';

	public function __construct(  ) {

		parent::__construct(  );

	}
}

/**
 * MySQL.Overview -> Tables unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Tables extends Unit {

    public $name = 'Tables';
    public $title = 'Tables';
    public $type = 'static';

    public function html() {

        $result = MySQL::tables();

        if (!empty($result)) {
            foreach ($result as $key => $_data) {

                $info = [];
                $info[] = [
                    ['val' => __('Engine', 'dev-studio')],
                    ['val' => $_data['ENGINE']]
                ];
                $info[] = [
                    ['val' => __('Row Format', 'dev-studio')],
                    ['val' => $_data['ROW_FORMAT']]
                ];
                $info[] = [
                    ['val' => __('Rows', 'dev-studio')],
                    ['val' => $_data['TABLE_ROWS']]
                ];
                $info[] = [
                    ['val' => __('Avg Row Length', 'dev-studio')],
                    ['val' => $_data['AVG_ROW_LENGTH']]
                ];
                $info[] = [
                    ['val' => __('Data Length', 'dev-studio')],
                    ['val' => $_data['DATA_LENGTH']]
                ];
                $info[] = [
                    ['val' => __('Index Length', 'dev-studio')],
                    ['val' => $_data['INDEX_LENGTH']]
                ];
                $info[] = [
                    ['val' => __('Auto Increment', 'dev-studio')],
                    ['val' => $_data['AUTO_INCREMENT']]
                ];
                $info[] = [
                    ['val' => __('Create Time', 'dev-studio')],
                    ['val' => $_data['CREATE_TIME']]
                ];
                $info[] = [
                    ['val' => __('Collation', 'dev-studio')],
                    ['val' => $_data['TABLE_COLLATION']]
                ];

                $this->data[] = [
                    'class' => 'info',
                    'cols' => [
                        ['val' => $_data['TABLE_NAME']],
                        ['val' => $_data['SIZE']],
                        ['val' => $_data['TABLE_ROWS']]
                    ],
                    'info' => DevStudio()->template('data/simple-array', [
                        'title' => __('Table:', 'dev-studio').' '. $_data['TABLE_NAME'],
                        'rows' => $info
                    ])
                ];
            }

            return DevStudio()->template('data/table', [
                'headers' => [
                    ['title' => __('Table name', 'dev-studio')],
                    ['title' => __('Size, MB', 'dev-studio')],
                    ['title' => __('Rows', 'dev-studio')],
                ],
                'rows' => $this->data
            ]);
        } else
            return Utils::no_data();
    }
}

/**
 * MySQL.Overview -> Variables unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Variables extends Unit {

    public $name = 'Variables';
    public $title = 'Variables';
    public $type = 'static';

    public function html() {

        $result = MySQL::variables();

        if (!empty($result)) {
            foreach ($result as $key => $value) {
                $this->data[] = [
                    'cols' => [
                        ['val' => $key],
                        ['val' => $value]
                    ]
                ];
            }

            return DevStudio()->template('data/table', [
                'headers' => [
                    ['title' => __('Variable name', 'dev-studio')],
                    ['title' => __('Value', 'dev-studio')],
                ],
                'rows' => $this->data
            ]);
        } else
            return Utils::no_data();
    }
}