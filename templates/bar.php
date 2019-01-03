<?php

$options = DevStudio()->options();

// DevStudio disabled
if (!DevStudio()->bar_enabled() || !DevStudio()->access('bar')) return;

$chevron = $options['bar']['expand']==='yes' ? 'up':'down';

//$html = '<div id="dev-studio-bar" class="'.($options['bar']['expand'] ? 'show':'').($options['bar']['expand']=='yes' ? ' expand':'').'">';
$html = '<div id="dev-studio-bar">';
    $html .= '<div class="ds-bar-icon"><span class="fa fa-chevron-'.esc_attr($chevron).'"></span></div>';
    $html .= '<div class="ds-bar-wrap">';
        $html .= '<div class="ds-bar-ds"><img src="'.esc_url(DevStudio()->url('assets')).'images/logo-sm.png"></div>';
        $html .= '<div class="ds-bar-inner"></div>';
    $html .= '</div>';
$html .= '</div>';

echo $html;