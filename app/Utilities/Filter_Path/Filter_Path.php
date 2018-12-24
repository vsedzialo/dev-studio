<?php
namespace DevStudio\Utilities;

use DevStudio\Helpers\Utils;

class Filter_Path extends \DevStudio\Core\Abstracts\Utility {

	public $name = 'Filter_Path';
    public $short = 'fp';

    public $applied = 0;
    public $uid = 0;
    public $data = [
        'home' => [
            'applied' => []
        ]
    ];

	public function __construct( $params ) {
        
        $this->pages = [
            'home' => [
                'title' => __('Home', 'dev-studio'),
                'view' => 'home_view',
                'model' => 'home_model',
            ],
            /*
            'analysis' => [
                'title' => __('Filter analysis', 'dev-studio'),
                'desc' => __('Step 2', 'dev-studio'),
                'view' => 'analysis_view',
            ],
            */
        ];
        
		parent::__construct( $params );
  
		$this->home_model();
        
        // Add AJAX handlers
        add_action('wp_ajax_dev_studio', [$this, 'ajax']);
        add_action('wp_ajax_nopriv_dev_studio', [$this, 'ajax']);
  
	}
    
    /**
     * AJAX requests
     *
     */
    public function ajax() {
        $response = [
            'result' => 'ok',
            '_REQUEST' => $_REQUEST
        ];
        switch ( $_REQUEST['request'] ) {
            case 'fp_filters':
                break;
        }
        
        wp_send_json( $response );
        wp_die();
    }
    
    
    public function init() {
    
    }
	
    public function home_view() {
     
	    $html = '';
    
	    // Form
        $html .= '<div class="ds-row" style="margin-bottom:4px">';
            $html .= '<div class="ds-col">'.__('Enter filter for analysis', 'dev-studio').'</div>';
        $html .= '</div>';
        
        $html .= '<div class="ds-row">';
            $filter = isset($this->utility_options['args']['filter']) ? $this->utility_options['args']['filter']:'';
            $html .= '<div class="ds-col ds-col-text">';
                $html .= '<input type="text" id="ds-fp-filter" value="'.esc_attr($filter).'">';
                $html .= '<button id="ds-fp-button">'.__('Save & Reload', 'dev-studio').'</button>';
            $html .= '</div>';
        $html .= '</div>';
    
        // Result
    
    
    
        if (isset($this->utility_options['args']['filter'])) {
            
            $data = $this->load_data();
    
            if (
                !empty($data) &&
                isset($data['home']) &&
                isset($data['home']['filter']) &&
                $data['home']['filter'] == $this->utility_options['args']['filter']
            ) {
                $html .= '<div class="title" style="margin-top:15px">' . count($data['home']['applied']) . ' ' . __('applyings', 'dev-studio') . '</div>';
        
                foreach ($data['home']['applied'] as $key => $calls) {
            
                    $time = end($calls)['time'] - $calls[0]['time'];
            
                    $source_value = $this->get_formatted_text($calls[0]['value'], $calls[0]['type']);
                    $source_type = $calls[0]['type'];
                    $source_length = $calls[0]['length'];
                    
                    $result_value = $this->get_formatted_text(end($calls)['value'], end($calls)['type']) ;
                    $result_type = end($calls)['type'];
                    $result_length = end($calls)['length'];
            
                    $rows = [
                        [
                            ['val' => __('Script', 'dev-studio'), 'style' => 'width:120px'],
                            ['val' => $calls[0]['file'] . ', line ' . $calls[0]['line']],
                        ],
                        [
                            ['val' => __('Callbacks', 'dev-studio')],
                            ['val' => count($calls) - 1],
                        ],
                        [
                            ['val' => __('Time', 'dev-studio')],
                            ['val' => \DevStudio\Helpers\Utils::time($time, 2)],
                        ]
                    ];
            
                    // Info block
                    $info_html = '';
                    $before = $before_length = $before_type = '';
            
                    $info_html .= '<div class="ds-ui-block-info-wr" data-id="applied-' . $key . '">';
            
                    foreach ($calls as $ckey => $call) {
                        if (!isset($call['init'])) {
                            $info = [];
                            if (!empty($call['func'])) {
                                $info[] = [
                                    ['val' => __('Function', 'dev-studio'), 'style' => 'width:120px'],
                                    ['val' => $call['func'], 'style' => 'color:#0073aa'],
                                ];
                            }
                            if (!empty($call['component'])) {
                                $info[] = [
                                    ['val' => __('Component', 'dev-studio')],
                                    ['val' => $call['component']['name']],
                                ];
                            }
                            if (!empty($call['file'])) {
                                $info[] = [
                                    ['val' => __('Script', 'dev-studio')],
                                    ['val' => $call['file'] . ', line ' . $call['line']],
                                ];
                            }
                            $info[] = [
                                ['val' => __('Time', 'dev-studio')],
                                ['val' => \DevStudio\Helpers\Utils::time($call['time'] - $time, 2)],
                            ];
                    
                            $info_html .= '<div class="ds-ui-block-info ' . ($before != $call['value'] ? 'ds-hl' : '') . '">';
                            $info_html .= '<div class="ds-ui-block-number">' . $ckey . '</div>';
                            $info_html .= DevStudio()->template('data/simple-array', ['rows' => $info]);
    
                            $_before = $this->get_formatted_text($before, $before_type) ;
                            $_after = $this->get_formatted_text($call['value'], $call['type']) ;
                            
                            $info_html .= '<div class="ds-ui-lr">';
                            $info_html .= '<div class="ds-ui-left">';
                            $info_html .= '<div class="ds-ui-label">' . __('Before', 'dev-studio') . '</div>';
                            $info_html .= '<div class="ds-ui-data">' . $_before . '</div>';
                            $info_html .= '<div class="ds-ui-info">' . ucfirst($before_type) . ', ' . __('Length', 'dev-studio') . ': ' . $before_length . '</div>';
                            $info_html .= '</div>';
                            $info_html .= '<div class="ds-ui-right">';
                            $info_html .= '<div class="ds-ui-label">' . __('After', 'dev-studio') . '</div>';
                            $info_html .= '<div class="ds-ui-data">' . $_after . '</div>';
                            $info_html .= '<div class="ds-ui-info">' . ucfirst($call['type']) . ', ' . __('Length', 'dev-studio') . ': ' . $call['length'] . '</div>';
                            $info_html .= '</div>';
                            $info_html .= '</div>';
                            $info_html .= '</div>';
                        }
                        $before = $call['value'];
                        $before_length = $call['length'];
                        $before_type = $call['type'];
                        $time = $call['time'];
                    }
                    $info_html .= '</div>';
            
                    $html .= $info_html;
            
            
                    $html .= '<div class="ds-ui-block ds-ui-link ' . ($source_value !== $result_value ? 'ds-hl' : '') . '" data-id="applied-' . $key . '">';
                    $html .= '<div class="ds-ui-block-number">' . $key . '</div>';
            
                    $html .= DevStudio()->template('data/simple-array', [
                        'rows' => $rows
                    ]);
            
                    $html .= '<div class="ds-ui-lr">';
                    $html .= '<div class="ds-ui-left">';
                    $html .= '<div class="ds-ui-label">' . __('Source', 'dev-studio') . '</div>';
                    $html .= '<div class="ds-ui-data">' . $source_value . '</div>';
                    $html .= '<div class="ds-ui-info">' . ucfirst($source_type) . ', ' . __('Length', 'dev-studio') . ': ' . $source_length . '</div>';
                    $html .= '</div>';
                    $html .= '<div class="ds-ui-right">';
                    $html .= '<div class="ds-ui-label">' . __('Result', 'dev-studio') . '</div>';
                    $html .= '<div class="ds-ui-data">' . $result_value . '</div>';
                    $html .= '<div class="ds-ui-info">' . ucfirst($result_type) . ', ' . __('Length', 'dev-studio') . ': ' . $result_length . '</div>';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</div>';
            
                }
            } else {
                $html .= Utils::no_data();
            }
        }
        
	    return $html;
    }
    
    public function home_model() {
    
        if (isset($this->utility_options['args']['filter'])) {
            add_action($this->utility_options['args']['filter'], [$this, 'init_callback'], PHP_INT_MIN);
        }
    }
    
    public function init_callback(...$args) {
        global $wp_filter;

        $callbacks = [];
        $filter = $this->utility_options['args']['filter'];
    
        $this->applied++;
        $this->uid = 0;
        $this->data['home']['filter'] = $filter;
        $this->data['home']['applied'][$this->applied] = [];
    
        if (isset($wp_filter[ $filter ])) {
            
            foreach ($wp_filter[ $filter ]->callbacks as $priority=>$_callback) {
                foreach ($_callback as $idx => $callback) {
    
                    if ($this->me($callback, true)) {
                        $callbacks[$priority][$idx] = $callback;
                        
                        $caller = debug_backtrace()[2];
                        $this->data['home']['applied'][$this->applied][$this->uid]['file'] = $caller['file'];
                        $this->data['home']['applied'][$this->applied][$this->uid]['line'] = $caller['line'];
                        
                        $this->data($args);
                    }
                    
                    if (!$this->me($callback)) {
                        $callbacks[$priority][$idx] = $callback;
                        $accepted_args = $callback['accepted_args'];
                        
                        // Save callback data
                        $cb = \DevStudio\Helpers\Utils::callback_data($callback);
                        
                        if (isset($cb['name'])) {
                            $this->data['home']['applied'][$this->applied][$this->uid]['func'] = $cb['name'];
                        }
                        if (isset($cb['file'])) {
                            $this->data['home']['applied'][$this->applied][$this->uid]['file'] = $cb['file'];
                        }
                        if (isset($cb['line'])) {
                            $this->data['home']['applied'][$this->applied][$this->uid]['line'] = $cb['line'];
                        }
                        if (isset($cb['component'])) {
                            $this->data['home']['applied'][$this->applied][$this->uid]['component'] = $cb['component'];
                        }
                        
                        // Add intermediate callback
                        $idx = _wp_filter_build_unique_id( $this->utility_options['args']['filter'], [$this, 'interim_callback_'.($this->uid++)], $priority );
                        
                        $callbacks[$priority][$idx] = [
                            'function' => [$this, 'interim_callback'],
                            'accepted_args' => $callback['accepted_args']
                        ];
                    }
                }
            }
            
            // Add finish callback
            $idx = _wp_filter_build_unique_id( $this->utility_options['args']['filter'], [$this, 'finish_callback'], $priority );
            
            $callbacks[$priority][$idx] = [
                'function' => [$this, 'finish_callback'],
                'accepted_args' => $accepted_args
            ];
            
            $wp_filter[ $filter ]->callbacks = $callbacks;
        }
        
        $this->uid = 1;

        return isset($args[0]) ? $args[0]:null;
    }
    
    public function interim_callback(...$args) {
        
        $this->data($args);
    
        return isset($args[0]) ? $args[0]:null;
    }
    
    public function finish_callback(...$args) {
        
        // Save data to file
        $this->save_data();
    
        return isset($args[0]) ? $args[0]:null;
    }
    
    public function data(...$args) {
    
        $this->data['home']['applied'][$this->applied][$this->uid]['time'] = microtime( true );
        
        $value = isset($args[0][0]) ? $args[0][0]:null;
        $type = 'string';
        if (is_array($value)) $type = 'array';
        if (is_object($value)) $type = 'object';
    
        if ($type !== 'string') {
            $value = print_r($value, true);
        }
        $length = mb_strlen($value, 'UTF-8');
        $this->data['home']['applied'][$this->applied][$this->uid]['length'] = $length;
        $this->data['home']['applied'][$this->applied][$this->uid]['value'] = $length < 5000 ? $value:mb_substr($value, 0, 5000, 'UTF-8').'...';
        $this->data['home']['applied'][$this->applied][$this->uid]['type'] = $type;
        
        if ($this->uid == 0) {
            $this->data['home']['applied'][$this->applied][$this->uid]['init'] = true;
        }
        $this->uid++;
    }
    
    public function me($callback, $init_callback = false) {
        if (
            is_array($callback) &&
            isset($callback['function']) &&
            is_array($callback['function']) &&
            $callback['function'][0] instanceof $this
        ) {
            if ($init_callback && $callback['function'][1] !== 'init_callback') return false;
            return true;
        }
    }
}
