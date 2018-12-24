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
        //if (!DevStudio()->me()) {

            if (!empty($options['checkpoints']['actions'][$mode])) {
                foreach ($options['checkpoints']['actions'][$mode] as $action => $_data) {
                    $this->add($action);
                    //dump($action);
                }
            }
        //}
	}

    /**
     * Add checkpoint
     *
     * @since 1.0.0
     * @param $checkpoint
     */
	public function add( $checkpoint ) {

        // DevStudio disabled
        if (!DevStudio()->enabled()) return;

	    // Exclude app system request
        if (DevStudio()->me()) return;

        // Exclude WP system AJAX query
        if (Utils::exclude_wp_ajax()) return;


		if ( DevStudio()->mode === 'public' || DevStudio()->mode === 'ajax_public' ) {
			$actions = Data::$actions['public'];
		} else {
			$actions = Data::$actions['admin'];
		}

		$this->checkpoints[ $checkpoint ] = [
			'action'    => in_array( $checkpoint, $actions ) ? true : false
		];

		if ( ! DevStudio()->me() ) {
			add_action( $checkpoint, [ $this, 'save_data' ] );
		}
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

			foreach ( $units as $dot_unit ) {
				$ex = explode( '.', $dot_unit );

				// Unit exists
				if ( isset( $map[ $ex[0] ]['components'][ $ex[1] ]['units'][ $ex[2] ] ) ) {

                    $unit = $modules[ $ex[0] ]->components[ $ex[1] ]->units[ $ex[2] ];

                    // Save data to files except static units
                    if ($unit->type !== 'static') {

                        // Create directories
                        $this->mkdirs();

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

		$this->checkpoint = $_POST['checkpoint'];

        $ex = explode( '.', $_POST['dot_unit'] );

        $map = DevStudio()->map;
        $modules = DevStudio()->modules;

        if ( isset($map[ $ex[0] ]['components'][ $ex[1] ]['units'][ $ex[2] ] ) ) {
            $unit = $modules[$ex[0]]->components[$ex[1]]->units[$ex[2]];

            // Load data from files except static units
            if ($unit->type !== 'static') {
                if ($unit->space === 'default') {
                    $this->data_dir = DevStudio()->dir('storage') . 'data/' . $_POST['mode'] . '/' . $this->checkpoint;
                } else {
                    $this->data_dir = DevStudio()->dir('storage') . 'data/' . $unit->space;
                }
                $fname = $this->data_dir . '/' . $_POST['dot_unit'] . '.dat';
                if ( file_exists( $fname ) ) {
                    $unit->load( $this->data_dir, $_POST['dot_unit'] );
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

    /**
     * Create directories
     *
     * @since 1.0.0
     */
    public function mkdirs() {
		$storage_dir = DevStudio()->dir('storage');

		$this->checkpoint_mode_dir = $storage_dir . 'data/' . DevStudio()->mode;
        Utils::mkdir($this->checkpoint_mode_dir);

		$this->checkpoint_dir = $this->checkpoint_mode_dir . '/' . $this->checkpoint;
        Utils::mkdir($this->checkpoint_dir);

		// Global
        $this->global_dir = $storage_dir . 'data/global';
        Utils::mkdir($this->global_dir);
	}
}