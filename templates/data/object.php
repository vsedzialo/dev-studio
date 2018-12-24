<?php
if (is_array($data) && !empty($data['message'])) echo $data['message'];

if (!is_object($data)) return;

echo '<pre class="object">';
$html = print_r($data, true);
$html = preg_replace('#^([^ ]+ Object)#', '<span class="ds-object-name">$1</span>', $html);
echo $html;
echo '</pre>';