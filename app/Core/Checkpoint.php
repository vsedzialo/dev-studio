<?php
namespace DevStudio\Core;

use DevStudio\Helpers\Utils;

/**
 * Checkpoint class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Checkpoint {

	public $checkpoints = [];

	public $checkpoint;

	public $checkpoint_dir;

	public $checkpoint_mode_dir;

    public $global_dir;

    public $storage = 'file';

    /**
     * Checkpoint constructor.
     *
     * @since 1.0.0
     * @param $mode
     * @param $options
     */
	public function __construct($mode, $options) {

        // DevStudio disabled
        //if (!DevStudio()->enabled()) return;

        // Exclude WP system AJAX query
        //if (Utils::exclude_wp_ajax()) return;



	    // Add checkpoints
        if (!empty($options['checkpoints']['actions'][$mode])) {
            foreach ($options['checkpoints']['actions'][$mode] as $action => $_data) {
                $this->add($action);
            }
        }
	}

    /**
     * Add checkpoint
     *
     * @since 1.0.0
     * @param $checkpoint
     */
	public function add( $checkpoint ) {

        // Check if collect data or not
        if (!Utils::collect()) return;

		if ( DevStudio()->mode === 'public' || DevStudio()->mode === 'ajax_public' ) {
			$actions = Data::$actions['public'];
		} else {
			$actions = Data::$actions['admin'];
		}

		$this->checkpoints[ $checkpoint ] = [
			'action'    => in_array( $checkpoint, $actions ) ? true : false
		];

    	add_action( $checkpoint, [ $this, 'save_data' ] );
	}

    /**
     * Save data to file
     *
     * @since 1.0.0
     */
    public function save_data() {

        $this->checkpoint = current_action();

        if ( isset( $this->checkpoints[ $this->checkpoint ] ) ) {

            $units = Data::units();
            $map = DevStudio()->map;
            $modules = DevStudio()->modules;

            $storage_dir = DevStudio()->dir('storage');
            $this->checkpoint_dir = $storage_dir . 'data/' . DevStudio()->mode . '/' . $this->checkpoint;
            $this->global_dir = $storage_dir . 'data/global';

            foreach ( $units as $dot_unit ) {
                $ex = explode( '.', $dot_unit );

                // Unit exists
                if ( isset( $map[ $ex[0] ]['components'][ $ex[1] ]['units'][ $ex[2] ] ) ) {

                    $unit = $modules[ $ex[0] ]->components[ $ex[1] ]->units[ $ex[2] ];

                    // Save data to files except static units
                    if ($unit->type !== 'static') {

                        // Create directories
                        Storage::mkdirs($this->checkpoint);

                        if ($unit->space === 'default')
                            $unit->save($this->checkpoint_dir, $dot_unit);
                        else
                            $unit->save($this->global_dir, $dot_unit);
                    }
                }
            }
        }
    }

    /**
     * Load data from file
     *
     * @since 1.0.0
     * @return string
     */
    public function load_data() {

        $this->checkpoint = sanitize_text_field($_POST['checkpoint']);

        $ex = explode( '.', sanitize_text_field($_POST['dot_unit']) );

        $map = DevStudio()->map;
        $modules = DevStudio()->modules;

        if ( isset($map[ $ex[0] ]['components'][ $ex[1] ]['units'][ $ex[2] ] ) ) {
            $unit = $modules[$ex[0]]->components[$ex[1]]->units[$ex[2]];


            // Load data from files except static units
            if ($unit->type !== 'static') {

                if ($unit->space === 'default') {
                    $this->data_dir = DevStudio()->dir('storage') . 'data/' . sanitize_text_field($_POST['mode']) . '/' . $this->checkpoint;
                } else {
                    $this->data_dir = DevStudio()->dir('storage') . 'data/' . sanitize_text_field($unit->space);
                }

                if ($unit->file_data) {
                    $filename = $this->data_dir . '/' . sanitize_text_field($unit->file_data) . '.dat';
                    $fname = $unit->file_data;
                } else {
                    $filename = $this->data_dir . '/' . sanitize_text_field($_POST['dot_unit']) . '.dat';
                    $fname = sanitize_text_field($_POST['dot_unit']);
                }

                if ( file_exists( $filename ) ) {
                    //return $filename;
                    $unit->load( $this->data_dir, $fname );
                    $html = $unit->html();
                    return $html;
                } else
                    return Utils::no_data();
            } else {
                $html = $unit->html();
                return $html;
            }
        }
    }
}