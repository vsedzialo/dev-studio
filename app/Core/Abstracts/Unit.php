<?php
namespace DevStudio\Core\Abstracts;

use DevStudio\Core\Storage;
use DevStudio\Helpers\Utils;

/**
 * Unit abstract class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
abstract class Unit {

    public $name;

    public $space = 'default';

    // Set unit data type, static means collect data only by request
    public $type = 'dynamic';   // dynamic | static

    public $title;

    public $file_data;

    public $tabTitle;

    public $html;

    public $data = [];

    public $options = [];

    public $default_options = [
        'data-info' => true
    ];

    /**
     * Unit constructor.
     *
     * @since 1.0.0
     * @param Component $component
     */
    public function __construct(Component $component) {
        
        if (empty($this->title)) {
            $this->set_title($this->title);
        }

        $this->options = array_replace_recursive($this->default_options, $this->options);
    }

    /**
     * Set unit title
     *
     * @since 1.0.0
     * @param $title
     */
    public function set_title($title) {
        $this->title = function_exists('__') ? __($title, 'dev-studio') : $title;
    }

    /**
     * Get unit data
     *
     * @since 1.0.0
     */
    public function data() {
    }

    /**
     * Encode unit data
     *
     * @since 1.0.0
     * @param $data
     * @return mixed|string
     */
    public function encode_data($data) {
        $result = json_encode($data);

        if ($result) {
            return $result;
        } else {
            echo '<pre>'.print_r(json_last_error_msg(), true).'</pre>';
            exit;
        }
    }

    /**
     * Decode unit data
     *
     * @since 1.0.0
     * @param $data
     * @return array|mixed|object
     */
    public function decode_data($data) {
        $result = json_decode($data, true);

        if ($result) return $result;
    }

    /**
     * Save unit data to file
     *
     * @since 1.0.0
     * @param $dir
     * @param $fname
     */
    public function save($dir, $fname) {

        // Check if collect data or not
        if (!Utils::collect()) return;

        if ($this->type !== 'static') {

            if ($this->file_data) {
                $filename = $dir . '/' . $this->file_data . '.dat';
                $key = $this->file_data;
            } else {
                $filename = $dir . '/' . $fname . '.dat';
                $key = $fname;
                if ($this->space !== 'default' && file_exists($filename)) return;
            }

            if (!file_exists($filename)) {
                // Form unit data
                DevStudio()->stats['data'][$key]['data_start'] = microtime(true);
                $this->data();
                DevStudio()->stats['data'][$key]['data_end'] = microtime(true);

                if (!empty($this->data)) {
                    $data = $this->encode_data($this->data);

                    DevStudio()->stats['data'][$key]['save_start'] = microtime(true);
                    Storage::save($filename, $data, $key);
                    DevStudio()->stats['data'][$key]['save_end'] = microtime(true);
                }
                unset($this->data);
            }
        }

    }

    /**
     * Load unit data from file
     *
     * @since 1.0.0
     * @param $dir
     * @param $fname
     */
    public function load($dir, $fname) {
        if ($this->file_data) {
            $filename = $dir . '/' . $this->file_data . '.dat';
        } else {
            $filename = $dir . '/' . $fname . '.dat';
        }
        $data = Storage::load($filename);
        $this->data = $this->decode_data($data);
    }

    /**
     * Get html
     *
     * @since 1.0.0
     * @return string
     */
    public function html() {
        $rows = [];
        foreach ($this->data as $key => $value) {
            if (is_array($value) && isset($value['type'])) {
                $value['val'] = $key;
                $rows[] = $value;
            } else {
                if (is_array($value)) {
                    $rows[] = [
                        'cols' => [
                            ['val' => $key],
                            $value
                        ]
                    ];
                } else {
                    $rows[] = [
                        'cols' => [
                            ['val' => $key],
                            ['val' => $value]
                        ]
                    ];
                }
            }
        }

        return DevStudio()->template('data/table', [
            'rows' => $rows
        ]);
    }

}
