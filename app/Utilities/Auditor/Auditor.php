<?php
namespace DevStudio\Utilities;

class Auditor extends \DevStudio\Core\Abstracts\Utility {

	public $name = 'Auditor';
	//public $title = 'Filter Path';
    
    public function __construct( $params ) {
        
        parent::__construct( $params );
        
        $this->units = [
            'fp' => [
                'title' => __('Auditor', 'dev-studio'),
                //'form' => 'fp_form',
                //'process' => 'fp_process',
                //'result' => 'fp_result',
            ]
        ];
        
        
        
	}
    
    public function init() {
        echo 'This is a form';
    }
	
    public function fp_form() {
	    echo 'This is a form';
    }
    
    public function fp_init() {
        echo 'This is a form';
    }
    
    public function fp_process() {
        $this->data = [
            'filter' => 'init',
            'calls' => [
                [
                
                ]
            ]
        ];
    }
    
    public function fp_result() {
	    
	    print_r($this->data);

    }
    
    
    
}
