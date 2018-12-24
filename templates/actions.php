<?php
$actions = DevStudio()->app_load($data['mode'].'/actions');
$options = DevStudio()->options();

if (!empty($actions)) {
    $start = false;
    $hidden = [];
    $hidden = [];

    // Not available actions
    echo '<div class="cp-not-available">';
    foreach($actions as $action=>$status) {
        if (!$status && $action!='dev-studio/init') {
            echo '<div class="cp-wrapper">';
            echo '<div class="cp-type"></div>';
            echo '<div class="cp-name">'.htmlspecialchars($action).'</div>';
            echo '</div>';
        }
    }
    echo '</div>';

    // Dev-studio/init
    echo '<div class="cp-wrapper start">';
    echo '<div class="cp-type"></div>';
    echo '<div class="cp-name">'.htmlspecialchars('dev-studio/init').'</div>';
    echo '<div class="cp-start"><span class="fa fa-chevron-down"></span></div>';
    echo '</div>';

    // Available
    foreach($actions as $action=>$status) {
        if ($status) {
            $checked = '';
            if (isset($options['checkpoints']['actions'][$data['mode']]) && isset($options['checkpoints']['actions'][$data['mode']][$action])) {
                $checked = ' checked';
            }
            if (isset($actions[$action])) {
                $type = 'wp';
                $symbol = 'C';
            }

            echo '<div class="cp-wrapper">';
            echo '<div class="cp-type"></div>';
            echo '<div class="cp-name">' . htmlspecialchars($action) . '</div>';
            echo '<div class="cp-check"><input type="checkbox"' . $checked . ' data-action="' . esc_attr($action) . '"></div>';
            echo '</div>';
        }
    }
}