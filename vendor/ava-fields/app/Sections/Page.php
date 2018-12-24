<?php
namespace DS_AVAFields\Sections;

/**
 * Page section class
 *
 * Allow to add sections to custom container
 *
 * @category   Wordpress
 * @package    ava-fields
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Page extends \DS_AVAFields\Core\Abstracts\Section {

    /**
     * Constructor.
     *
     * @param $params
     */
    public function __construct( $container_id, $id, $params ) {
        parent::__construct( $container_id, $id, $params );

    }

    public function render( $args ) {

        $classes = [];
        $tabs = [];

        if ( $args['active'] == $this->id ) {
            $classes[] = 'active';
        }

        $this->html = '<div class="avaf-section ' . esc_attr( implode( ' ', $classes ) ) . '" data-section="' . esc_attr( $this->id ) . '">';

        if (!empty($this->params['tabs'])) {
            $this->html .= '<div class="avaf-nav-tabs">';
            $active = true;
            foreach ($this->params['tabs'] as $tab_key => $tab) {
                $this->html .= '<div class="avaf-nav-tab'.($active ? ' active':'').'" data-tab="'.esc_attr( $tab_key ).'">';
                $this->html .= $tab;
                $this->html .= '</div>';
                $tabs[$tab_key] = '';
                $active = false;
            }
            $this->html .= '</div>';
        }

        $this->html .= '<form class="avaf-form" data-nonce="de9b85ac62" enctype="multipart/form-data">';

        foreach ( $this->fields as $field_id => $field ) {
            $htm = $field->render();
            if (!empty($this->params['tabs']) && isset($field->params['tab'])) {

                $tabs[$field->params['tab']] .= $htm;
            } else
                $this->html .= $htm;
        }

        if (!empty($this->params['tabs'])) {
            $active = true;
            foreach($tabs as $tab_key=>$htm) {
                $this->html .= '<div class="avaf-tab'.($active ? ' active':'').'" data-tab="'.esc_attr( $tab_key ).'">';
                $this->html .= $htm;
                $this->html .= '</div>';
                $active = false;
            }
        }


        $this->html .= '</form>';

        $this->html .= '</div>';

        return $this->html;
    }

    public function this() {
        return $this;
    }
}

