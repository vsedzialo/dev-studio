<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'AVA_Field_Sortable' ) ) {
    /**
     * Sortable field
     *
     * @category   Wordpress
     * @package    ava-fields
     * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
     * @version    Release: 1.0.0
     * @since      Class available since Release 1.0.0
     */
	class AVA_Field_Sortable extends \DS_AVAFields\Core\Abstracts\Field {
        
		public $type = 'sortable';
		
		public function __construct( $container_id, $section_id, $id, $params ) {
			parent::__construct( $container_id, $section_id, $id, $params );

			$this->add_handler( $this->field_dir . 'assets/handler.js' );
            DS_AVA_Fields()->add_script( 'avaf-sortable', $this->field_dir . 'assets/Sortable.js' );
		}

		public function build() {
		    $value = $this->get_value( $this->id );

		    if (!empty($value) && is_array($value)) {
		        $items = [];
		        foreach($value as $item) {
		            if (isset($this->params['items'][$item])) {
                        $items[$item] = $this->params['items'][$item];
                    }
                }
            } else
                $items = $this->params['items'];
		    
		    $this->html = '';
            //$this->html .= print_r($items, true);
            
            $this->html .= '<div class="avaf-sortable-wrapper">';
            $this->html .= '<div class="avaf-sortable" >';
            foreach ( $items as $value => $data ) {
                $this->html .= '<div class="avaf-sortable-item" data-value="'.esc_attr($value).'">';
                    $this->html .= '<div class="avaf-sortable-handle"><svg xmlns="http://www.w3.org/2000/svg" width="15px" viewBox="0 0 50 50"><path style="stroke:#ddd;line-height:normal;text-indent:0;text-align:start;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#ccc;text-transform:none;block-progression:tb;isolation:auto;mix-blend-mode:normal" d="M 24.984375 2 L 18.625 8.3261719 C 18.228 8.7231719 18.228 9.368625 18.625 9.765625 C 19.022 10.162625 19.6655 10.162625 20.0625 9.765625 L 24 5.84375 L 24 15 L 3 15 L 3 17 L 47 17 L 47 15 L 26 15 L 26 5.84375 L 29.9375 9.765625 C 30.3345 10.162625 30.979953 10.162625 31.376953 9.765625 C 31.773953 9.368625 31.772 8.7231719 31.375 8.3261719 L 25.015625 2 L 25 2.015625 L 24.984375 2 z M 3 24 L 3 26 L 47 26 L 47 24 L 3 24 z M 3 33.013672 L 3 35.013672 L 23.998047 35.013672 C 23.998245 35.0144 24 35.014909 24 35.015625 L 24 44.171875 L 20.0625 40.248047 C 19.6655 39.851047 19.022 39.851047 18.625 40.248047 C 18.228 40.645047 18.228 41.2905 18.625 41.6875 L 24.984375 48.013672 L 25 47.998047 L 25.015625 48.013672 L 31.375 41.6875 C 31.772 41.2905 31.773953 40.647 31.376953 40.25 C 30.979953 39.853 30.3345 39.853 29.9375 40.25 L 26 44.171875 L 26 35.015625 C 26 35.014909 26.001767 35.0144 26.001953 35.013672 L 47 35.013672 L 47 33.013672 L 3 33.013672 z" font-weight="400" font-family="sans-serif" white-space="normal" overflow="visible"/></svg></div>';
                    $this->html .= '<div class="avaf-sortable-html">'.$data['html'].'</div>';
                $this->html .= '</div>';
            }
            $this->html .= '</div>';
            $this->html .= '</div>';
		}
	}
}

