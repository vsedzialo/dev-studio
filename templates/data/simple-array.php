<?php

use DevStudio\Helpers\Utils;

if (empty($data) || !is_array($data)) return;

$html = '';
$info = '';

global $col;

if (!empty($data['title'])) $html .=  '<div class="title">'.$data['title'].'</div>';
if (!empty($data['h2'])) $html .=  '<h2>'.$data['h2'].'</h3>';
if (!empty($data['h3'])) $html .=  '<h3>'.$data['h3'].'</h3>';

$html .= '<div>';
    $html .= '<table class="data-table" cellspacing="1">';
        if (!empty($data['headers'])) {
            $html .= '<thead>';
                foreach($data['headers'] as $header) $html .= '<th>'.$header['title'].'</th>';
            $html .= '</thead>';
        }
        $html .= '<tbody>';

        foreach($data['rows'] as $cols) {
            $html .= '<tr>';
                if (!empty($row['info']) ) {
                    $info .= '<div id="info-'.esc_attr($id).'" style="display:none">'.$row['info'].'</div>';
                }

                foreach($cols as $col) {
                    $html .= '<td';
                        $html .= !empty( $col['class'] ) ? ' class="'.esc_attr( $col['class'] ).'"' : '';
                        $html .= !empty( $col['style'] ) ? ' style="'.esc_attr( $col['style'] ).'"' : '';
                        $html .= !empty( $col['attrs'] ) ? ' '.implode(' ', $col['attrs']) : '';
                    $html .= '>';
                    
                    //$html .= '<pre>'.print_r($col['val'], true).'</pre>';
                    if (is_array($col['val'])) {
                        foreach($col['val'] as $key=>$val) $col['val'][$key] = Utils::prepare_string(['val'=>$val]);
                        $html .= implode('<br/>', $col['val']);
                    } else {
                        $html .= Utils::prepare_string($col);
                    }
                    $html .= '</td>';
                }
            $html .= '</tr>';
        }
        $html .= '</tbody>';
    $html .= '</table>';
$html .= '</div>';
echo $html;

// Info
echo  $info;

