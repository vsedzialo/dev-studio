<?php
    $options = DevStudio()->options();
    $mode = sanitize_text_field($_REQUEST['mode']);
    $enabled = DevStudio()->options()['general']['enabled']==='yes' ? '':' ds-off';
?>
<div id="dev-studio" class="dev-studio<?php echo $enabled; ?>">

    <div class="ds-left-panel">
        
        <div class="ds-logo">
            <img src="<?php echo DevStudio()->url('assets'); ?>images/logo.png">
            <div>
                <div class="ds-on-off<?php echo $enabled; ?>">
                    <div class="ds-slider"></div>
                    <div class="ds-items"><div class="ds-on">On</div><div class="ds-off">Off</div></div>
                </div>
            </div>
        </div>

        <div class="ds-modules">
            <?php
            $active_module = '';
            $modules_order = $options['general']['appearance']['modules_order'];
        
            $unit_init = $options['general']['appearance']['unit_init'];
            if (!empty($unit_init)) {
                $parts = explode('.', $unit_init);
                $active_module = isset(DevStudio()->modules()[$parts[0]]) ? $parts[0]:'';
            }

            foreach($modules_order as $module_order) {
                foreach(DevStudio()->modules() as $module_name => $module) {
                    if ($module_order == $module_name ) {
                        if (!$active_module) $active_module = $module_name;
                        echo '<div class="ds-tab-module' . ($module_name == $active_module ? ' ds-active' : '') . '" data-module="' . esc_attr($module_name) . '">';
                        echo  $module->title;
                        echo '</div>';
                    }
                }
            }
            ?>
        </div>
        <div class="ds-bg-off"></div>
    </div>
    <div class="ds-right-panel">
        
        <div class="ds-header">

            <div class="ds-header-bar">

                <div id="ds-ajax" class="<?php preg_match('#^ajax#', $mode) ? 'ds-active':''; ?>"><?php echo __('AJAX mode', 'dev-studio'); ?></div>
                
                <div id="ds-ajax-test"><div><?php echo __('AJAX test', 'dev-studio'); ?></div></div>
                
                <div class="ds-checkpoints">
                    <div class="ds-select">
                        <div>
                            <select id="checkpoint">
                                <?php
                                if (!empty($options['checkpoints']['actions'][$mode])) {
                                    foreach ($options['checkpoints']['actions'][$mode] as $checkpoint => $_data) {
                                        echo '<option value="' . $checkpoint . '">' . $checkpoint . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="ds-icons"><span class="fa ds-tip fa-cog" data-tippy-content="<?php echo __('Checkpoints', 'dev-studio'); ?>"></span></div>
                </div>

            </div>
        </div>

        <div class="ds-ws">

            <div class="ds-tabs-components"></div>
            
            <div class="ds-tabs-data">

                <div class="ds-tabs">
                    <div class="ds-tab ds-tab-checkpoints ds-active"><?php echo __('Checkpoints', 'dev-studio'); ?></div>
                    <div class="ds-tabs-units"></div>
                </div>

                <?php
                $data_info = !empty($options['data_info']) && filter_var($options['data_info'], FILTER_VALIDATE_BOOLEAN);
                ?>

                <div id="ds-data-container" class="ds-data <?php echo $data_info ? 'ds-col-2':'';?>">
                    <div id="dev-studio-actions">
                        <?php
                        echo DevStudio()->template->load( 'actions', [
                            'mode' => $mode
                        ]);
                        ?>
                    </div>
                    <div id="dev-studio-data"></div>
                    <div id="dev-studio-tools">
                        <div class="ds-tool ds-toggle-info"><span class="fa fa-chevron-<?php echo $data_info ? 'right':'left';?>"></span></div>
                        <a class="ds-tool ds-app-info" data-type="wordpress" title="<?php echo __('Wordpress','dev-studio'); ?>"><span class="fa fa-wordpress"></span></a>
                        <?php if (function_exists('is_woocommerce')) { ?>
                            <a class="ds-tool ds-app-info" data-type="woocommerce" title="<?php echo __('WooCommerce','dev-studio'); ?>"><span class="fa fa-shopping-cart"></span></a>
                        <?php } ?>
                        <a class="ds-tool ds-app-info" data-type="php" title="<?php echo __('PHP','dev-studio'); ?>"><span class="ds-tool-text">PHP</span></a>
                        <a class="ds-tool ds-app-info" data-type="database" title="<?php echo __('Database','dev-studio'); ?>"><span class="fa fa-database"></span></a>
                        <a class="ds-tool ds-app-info" data-type="server" title="<?php echo __('Server','dev-studio'); ?>"><span class="fa fa-server"></span></a>
                        <a class="ds-tool ds-settings" title="<?php echo __('Settings','dev-studio'); ?>"><span class="fa fa-cogs"></span></a>
                        <!--<a class="ds-tool ds-stats" title="<?php echo __('Dev Studio','dev-studio'); ?>"><span class="fa fa-bar-chart"></span></a>-->
                    </div>
                    <div id="dev-studio-data-info">
                        <div class="ds-data-info-control">
                            <span class="fa fa-tag ds-pin<?php echo $data_info ? ' ds-active':'';?>"></span>
                        </div>
                        <div class="ds-data-info"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Preloader -->
    <div class="ds-preloader">
        <div class="ds-pl-image"><img src="<?php echo DevStudio()->url('assets').'images/logo-loop.svg'; ?>"></div>
        <div class="ds-pl-text"></div>
    </div>

</div>