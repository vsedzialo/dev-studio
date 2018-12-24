<?php
namespace DevStudio\Core\Abstracts;

use DevStudio\Helpers\Utils;

/**
 * Utility abstract class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
abstract class Utility {

	public $name;
	public $short;
    public $page;
    
    //public $enabled = false;
    //public $step = 1;
    public $params = [];
    //public $upages = [];
    //public $options = [];
    public $utility_options = [];
    
    //public $data;

    /**
     * Utility constructor.
     *
     * @param $params
     */
	public function __construct( $params ) {
     
	    $this->params = $params;
        //$this->options = DevStudio()->options();
        $this->utility_options = isset(DevStudio()->options()['utilities'][$this->name]) ? DevStudio()->options()['utilities'][$this->name]:[];
        $this->page = $this->get_page(isset($params['page']) ? $params['page']:null);
        
        //$this->mkdirs();
        
        // Test
        if (isset($params['args'])) $this->utility_options['args'] = $params['args'];
	}
    
    public function view( ) {
	    return '';
    }
    
    public function get_view($page) {
	    $page = $this->get_page($page);
    
        $method = $page.'_view';
    
        $html = '<div id="ds-util" class="ds-util ds-util-'.$this->short.'" data-util="'.esc_attr($this->name).'">';
	    if (method_exists($this, $method)) {
            $html .= $this->$method();
        } else {
            $html .= $this->view();
        }
        $html .= '</div>';
	    return $html;
	}
    
    public function get_page($page) {
        if (!empty($page) && isset($this->pages[$page])) return $page;
        
        foreach($this->pages as $_page=>$_) return $_page;
    }
    
    public function get_header() {
	    $html = '';
        //$html .= '<div class="ds-util-header">';
    
        $html .= '<div class="ds-utilh-img"><img src="'.(DevStudio()->utilities->get_icon($this->params['icon'])).'"></div>';
        $html .= '<div class="ds-utilh-title">'.$this->params['title'].'</div>';
        $html .= '<div class="ds-utilh-control"></div>';
        //$html .= print_r($this->params, true);
        //$html .= '</div>';
        
        return $html;
        
    }
    
    public function get_assets() {
    
        $html = '';
	    if (isset($this->params['styles']) && file_exists($this->params['styles'])) {
            $html .= '<style>';
            $html .= file_get_contents($this->params['styles']);
            $html .= '</style>';
        }
    
        if (isset($this->params['scripts']) && file_exists($this->params['scripts'])) {
            $html .= '<script>';
            $html .= file_get_contents($this->params['scripts']);
            $html .= '</script>';
        }
        return $html;
    }
    
    public function enabled($status = true) {
        $options = DevStudio()->options();
        $options['utilities'][$this->name]['enabled'] = $status ? 'yes':'no';
        DevStudio()->save_options($options);
    }
    
    /**
     * Create utility storage directory
     *
     */
    public function mkdirs() {
        $utils_dir = DevStudio()->dir('storage') . 'utilities';
        Utils::mkdir($utils_dir);

        $util_dir = $utils_dir . '/' . $this->name;
        Utils::mkdir($util_dir);
    }
    
    /**
     * Check on file existing in utility storage directory
     *
     */
    public function file_exists($fname) {
        $filename = DevStudio()->dir('storage').'utilities/'.$this->name.'/'.$fname.'.dat';
        return file_exists($filename);
    }
    
    /**
     * Save custom data
     *
     * @param $name
     * @param $data
     * @param bool $json
     */
    public function save($name, $data, $json=true) {
        $this->mkdirs();
        $util_dir = DevStudio()->dir('storage') . 'utilities/' . $this->name;
        $fname = $util_dir.'/'.$name.'.dat';
        file_put_contents($fname, $json ? json_encode($data):$data);
    }
    
    /**
     * Load custom data
     *
     * @param $name
     * @param $data
     * @param bool $json
     */
    public function load($name, $json=true) {
        $util_dir = DevStudio()->dir('storage') . 'utilities/' . $this->name;
        $fname = $util_dir.'/'.$name.'.dat';
        if (file_exists($fname)) {
            $data = file_get_contents($fname);
            return $json ? json_decode($data, true):$data;
        }
    }
    
    /**
     * Save utility data
     *
     */
    public function save_data() {
        $this->mkdirs();
        $util_dir = DevStudio()->dir('storage') . 'utilities/' . $this->name;
        $fname = $util_dir.'/data.dat';
        file_put_contents($fname, json_encode($this->data));
    }
    
    /**
     * Load utility data
     *
     */
    public function load_data() {
        $util_dir = DevStudio()->dir('storage') . 'utilities/' . $this->name;
        $fname = $util_dir.'/data.dat';
        if (file_exists($fname)) {
            $data = file_get_contents($fname);
            return json_decode($data, true);
        }
    }
    
    public function get_formatted_text($text, $type = 'string') {
        $text = htmlspecialchars($text);
        if ($type !== 'string') return '<pre class="' . esc_attr($type) . '">' . $text . '</pre>';
        return $text;
    }
    
    
}