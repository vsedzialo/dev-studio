<?php
namespace DevStudio\Core;

use DevStudio\Helpers\Utils;

/**
 * Utilities class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Utilities {

    public $registered = [];
    public $categories = [];
    public $loaded = [];

    public function __construct() {
        //add_action( 'dev-studio/init', [$this, 'utilities'] );
    
        $utils_dir = DevStudio()->dir('storage') . 'utilities';
        Storage::mkdir($utils_dir);
        
        $this->load_utilities();
    }
    
    public function register($params) {
    
        // Not passed an array
        if (!is_array($params)) return;
        
        // Not passed name or title or category
        if (!isset($params['name']) || !isset($params['title']) || !isset($params['category'])) return;
        
        // Utility already is registered
        if (isset($this->registered[$params['name']])) return;
    
        // Unknown category
        if (!is_array($params['category']) && !isset($this->categories[$params['category']])) return;
        
        // Check on filename and class existing
        if (!isset($params['filename']) || !file_exists($params['filename'])) return;
        
        //require_once();
    
        $this->registered[$params['name']] = $params;
        
        $utility = [
            'title' => $params['title']
        ];
        
        // Define category
        $cat_title = '';
        if (is_array($params['category'])) {
            foreach($params['category'] as $cat_key=>$cat_title) $params['category'] = $cat_key;
        }
        
        if (!isset($this->categories[$params['category']])) {
            $this->categories[$params['category']] = $cat_title;
        }
        $this->registered[$params['name']]['category'] = $params['category'];
        
        // Icon
        /*
        if (isset($params['icon'])) {
            $utility['icon'] = $params['icon'];
        }
    
        $this->registered[$params['name']] = $utility;
        */
    
        
        // Load utility if enabled
        $options = DevStudio()->options();
        
        //if (isset($options['utilities'][$utility['name']]) && isset($options['utilities'][$utility['name']]['enabled']) && $options['utilities'][$utility['name']]['enabled'] == 'yes') {
    
        if (isset($options['utilities'][$params['name']]) && isset($options['utilities'][$params['name']]['enabled']) && $options['utilities'][$params['name']]['enabled'] == 'yes') {
            $this->loaded[$params['name']] = $this->get_obj($params);
            //call_user_func_array($utility['namespace'].'\\'.$utility['name'].'::init');
        }
    }
    
    /**
     * Register standart utilities
     *
     */
    protected function load_utilities() {
        $utilities = [
            [
                'name'  => 'Auditor',
                'title' => 'Auditor',
                'category' => [
                    'stats' => __('Stats', 'dev-studio')
                ],
                'namespace' => 'DevStudio\Utilities',
                'filename' => DevStudio()->dir('utilities') . 'Auditor/Auditor.php',
                'icon' => DevStudio()->url('utilities') . 'Auditor/auditor.png',
                'styles' => DevStudio()->dir('utilities') . 'Auditor/assets/styles.css',
                'scripts' => DevStudio()->dir('utilities') . 'Auditor/assets/scripts.css',
            ],
            [
                'name'  => 'Filter_Path',
                'title' => 'Filter Path',
                'category' => [
                    'debug' => __('Debug', 'dev-studio')
                ],
                'namespace' => 'DevStudio\Utilities',
                'filename' => DevStudio()->dir('utilities') . 'Filter_Path/Filter_Path.php',
                'icon' => DevStudio()->url('utilities') . 'Filter_Path/filter_path.png',
                'styles' => DevStudio()->dir('utilities') . 'Filter_Path/assets/styles.css',
                'scripts' => DevStudio()->dir('utilities') . 'Filter_Path/assets/scripts.js',
            ],
            
        ];
        
        foreach($utilities as $utility) {
            $this->register($utility);
        }
    }
    
    public function load($utility, $page=null) {
        if (isset($this->registered[$utility])) {
    
            $params = array_merge_recursive($this->registered[$utility], array('page' => $page));
            //return $params;
            $object = $this->get_obj($params);
            
            if (is_object($object)) {
                return [
                    'params'  => $this->registered[$utility],
                    'header'  => $object->get_header(),
                    'html'  => $object->get_view($page),
                    'assets'  => $object->get_assets(),
                    'pages' => $object->pages,
                    'page' => $object->page
                ];
            }
        
        }
    }
    
    public function get_obj($params) {
        if (!isset($params['filename']) || !file_exists($params['filename'])) return;
        
        require_once($params['filename']);
        $class_name = (isset($params['namespace']) ? $params['namespace'].'\\':'').$params['name'];
        return new $class_name($params);
    }
    
    public function get_icon($icon) {
        if (!empty($icon)) {
            return esc_url($icon);
        } else {
            return DevStudio()->url('assets').'images/utility.png';
        }
    }
}