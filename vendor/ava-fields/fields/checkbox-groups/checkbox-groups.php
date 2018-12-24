<?php
if (!defined( 'ABSPATH' )) {
	die( '-1' );
}

if (!class_exists( 'AVA_Field_Checkbox_Groups' )) {
    /**
     * Checkbox Groups field
     *
     * @category   Wordpress
     * @package    ava-fields
     * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
     * @version    Release: 1.0.0
     * @since      Class available since Release 1.0.0
     */
	class AVA_Field_Checkbox_Groups extends \DS_AVAFields\Core\Abstracts\Field
	{
		public $type = 'checkbox-groups';

		public function __construct($container_id, $section_id, $id, $params) {
			parent::__construct( $container_id, $section_id, $id, $params );

			$this->add_handler( $this->field_dir . 'assets/handler.js' );
		}

		public function build() {
            $value = (array)$this->get_value($this->id);
			
            foreach($this->params['groups'] as $group=>$group_data) {
                if (!empty($group_data['title'])) {
                    $this->html .= '<div class="avaf-cg-title">' . $group_data['title'] . '</div>';
                }
                $this->html .= '<div class="avaf-cgroup">';
                    $checked_parent = '';
                    foreach ($group_data['childs'] as $child_value => $child_title) {
                        if (in_array($child_value, $value)) {
                            $checked_parent = ' checked';
                            break;
                        }
                    }
                    $this->html .= '<div class="avaf-cg-parent"><input type="checkbox" data-group="' . esc_attr($group) . '"'.$checked_parent.'>&nbsp;' . $group_data['parent'] . '</div>';
                    $this->html .= '<div class="avaf-cg-childs">';
                    foreach ($group_data['childs'] as $child_value => $child_title) {
                        $checked = in_array($child_value, $value) ? ' checked':'';
                        $this->html .= '<div><input type="checkbox" data-group="' . esc_attr($group) . '" value="' . esc_attr($child_value) . '"'.$checked.'>&nbsp;' . $child_title . '</div>';
                    }
                    $this->html .= '</div>';
                $this->html .= '</div>';
            }
		}

		public function storage_value( $value ) {
			return \DS_AVAFields\Core\Utils::yes_no($value);
		}

	}
}

