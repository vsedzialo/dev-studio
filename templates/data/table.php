<?php
use DevStudio\Helpers\Utils;

if (!empty($data['rows']) && isset($data['rows']['message'])) {
    echo $data['rows']['message'];
    return;
}

$html = '';

/**
 * Panel
 *
 **/
if (!empty($data['panel'])) {
    $html .= '<div class="ds-panel">';

    if (!empty($data['panel']['filters'])) {
        foreach($data['panel']['filters'] as $filter) {
            $html .= '<div class="ds-filter">';
            
            $label = !empty($filter['label']) ? '<div class="ds-filter-label">'.wp_kses_post($filter['label']).'</div>':'';
    
            $atts = [];
            if (!empty($filter['src'])) $atts[] = 'data-src="'.esc_attr($filter['src']).'"';
            if (!empty($filter['src_data'])) $atts[] = 'data-src_data="'.esc_attr($filter['src_data']).'"';
            if (!empty($filter['src_value'])) $atts[] = 'data-src_value="'.esc_attr($filter['src_value']).'"';
            
            // Select
            if ($filter['type'] === 'select') {
                $html .= $label . '<select class="ds-filter-el ds-filter-select" '.implode(' ', $atts).'>';
                foreach($filter['options'] as $opt_key=>$opt_val) $html .= '<option value="'.esc_attr($opt_key).'">'.wp_kses_post($opt_val).'</option>';
                $html .= '</select>';
            }
    
            // Checkbox
            if ($filter['type'] === 'checkbox') {
                $html .= '<input class="ds-filter-el ds-filter-checkbox" type="checkbox" value="1" '.implode(' ', $atts).'>' . $label;
            }
            $html .= '</div>';
        }
    }

    $html .= '</div>';
}



/**
 * Table
 *
 **/
if (!empty($data['title'])) $html .=  '<div class="title">'.$data['title'].'</div>';
if (!empty($data['h2'])) $html .=  '<h2>'.$data['h2'].'</h3>';
if (!empty($data['h3'])) $html .=  '<h3>'.$data['h3'].'</h3>';

$html .= '<table cellspacing="1" class="data-table '.(!empty( $data['class'] ) ? esc_attr( $data['class'] ) : '').'" cellspacing="1" style="'.(!empty( $data['style'] ) ? esc_attr( $data['style'] ) : '').'">';

// Headers
if (!empty($data['headers'])) {
    $html .= '<thead>';
    
    foreach((array)$data['rows'] as $key=>$row) {
        if (!empty($row['info']) || (isset($data['class']) && $data['class']==='info') ) $html .= '<th style="width:20px"></th>';
        break;
    }
    
    foreach($data['headers'] as $key=>$header) {
        
        $order = '';
        $order_class = '';
        $order_type = '';
        if (!empty($data['order']) && !empty($data['order'][$key])) {
            $fa_class = 'fa-sort';
            $order_class = 'ds-order';
            if (!empty($data['order'][$key]['dir'])) {
                $fa_class = 'fa-chevron-'.$data['order'][$key]['dir'];
                $order_class .= ' ds-order-'.$data['order'][$key]['dir'];
            }
            if (!empty($data['order'][$key]['type'])) {
                $order_type = ' data-otype="'.esc_attr($data['order'][$key]['type']).'"';
            }
            
            $order = '<span class="ds-order fa '.esc_attr($fa_class).'"></span>';
        }
        
        if ($header['title']==='{empty}') {
            $html .= '<th class="empty '.esc_attr(trim($order_class)).'"'.$order_type.'>&nbsp;'.$order.'</th>';
        } else {
            $html .= '<th class="'.esc_attr(trim($order_class)).'"'.$order_type.' noWrap>' . $header['title'] . $order.'</th>';
        }
    }
    $html .= '</thead>';
}

// Body
$html .= '<tbody>';
    $info = '';
    $pos = 0;
    foreach((array)$data['rows'] as $key=>$row) {
        $id = ++$pos;

	    if ( isset( $row['type'] ) && $row['type'] === 'title' ) {
            $html .= '<tr class="title">';
                $html .= '<td colspan="10">'.$row['val'].'</td>';
            $html .= '</tr>';
	    } else {
            $row['id'] = (isset($data['id']) ? $data['id'].'-':'') . 'row-'.$id;
            
            // Row attributes
            $row_atts = !empty($row['atts']) ? $row['atts']:[];
            $row_atts[] = 'data-pos = "' . $pos . '"';
            if (!empty($row['class'])) $row_atts[] = 'class = "'.esc_attr($row['class']).'"';
            if (!empty($row['id'])) {
                $row_atts[] = 'data-id = "' . esc_attr($row['id']) . '"';
                $row_atts[] = 'id = "' . esc_attr($row['id']) . '"';
            }
            $html .= '<tr '.implode(' ', $row_atts).'>';
            // $html .= '<tr'.(isset($row['class']) ? ' '.implode(' ', $row_atts).'class="'.esc_attr($row['class']).'"':'').(isset($row['id']) ? ' data-id="'.esc_attr($id).'" id="'.esc_attr($row['id']).'"':'').'>';

            if (!empty($row['info']) || (isset($data['class']) && $data['class']==='info') ) {
                $html .= '<td>';
                if (!empty($row['info'])) {
                    $info .= $this->add_info_block($row['id'], $row['info']);
                    $html .= '<span class="fa fa-eye"></span>';
                } else
                    $html .= '&nbsp;';
                $html .= '</td>';
            }

            foreach ( $row['cols'] as $col_id=>$col ) {
                $html .= '<td';
                    $html .= !empty( $col['class'] ) ? ' class="'.esc_attr( $col['class'] ).'"' : '';
                    $html .= !empty( $col['style'] ) ? ' style="'.esc_attr( $col['style'] ).'"' : '';
                    $html .= !empty( $col['attrs'] ) ? ' '.implode(' ', $col['attrs']) : '';
                $html .= '>';

                if (!empty($col['class']) && $col['class']==='ds-pos') {
                    $html .= $pos;
                } else {
                    // Value
                    if (is_array($col['val']) || is_object($col['val']) || (isset($col['type']) && $col['type'] === 'data-info')) {
            
                        $html .= '<span class="data-info ' . (empty($col['val']) ? 'empty' : '') . '">';
                        $eye = true;
                        if (is_array($col['val'])) {
                            $html .= !empty($col['val']) ? '[ Array ]' : '[ Empty array ]';
                            if (empty($col['val'])) $eye = false;
                        } else if (is_object($col['val'])) {
                            $html .= !empty($col['val']) ? '[ Object ]' : '[ Empty object ]';
                        } if (isset($col['text'])) {
                            $html .= $col['text'];
                        }
                        /* else if (isset($col['val'])) {
                            $html .= $col['val'];
                        }
                        */

                        if ($eye) {
                            $html .= ' <span class="fa fa-eye row-info" data-id="' . $row['id'] . '-' . $col_id . '" data-type="info"></span>';
                        }

                        $html .= '</span>';

                        $info .= $this->add_info_block($row['id'] . '-' . $col_id, $col['val']);
            
                    } else {
                        $html .= Utils::prepare_string($col);
                        if (isset($col['info'])) {
                            $html .= ' <span class="fa fa-eye row-info" data-id="' . $row['id'] . '-' . $col_id . '" data-type="info"></span>';
                            $info .= $this->add_info_block($row['id'] . '-' . $col_id, $col['info']);
                        }
                    }
                }
                
                $html .= '</td>';
            }
            $html .= '</tr>';
	    }
    }
    $html .= '</tbody>';
$html .= '</table>';
echo $html;

// Info
echo  $info;



