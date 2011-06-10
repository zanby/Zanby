<?php

require_once 'Console/ProgressBar.php';

class OptionalProgressBar extends Console_ProgressBar {

    var $_active = false;
    
    function OptionalProgressBar( $active, $formatstring, $bar, $prefill, $width, 
                                  $target_num, $options = array())
    {
	$this->_active = $active;
	if ( $this->_active) {
	    $this->Console_ProgressBar( $formatstring, $bar, $prefill, $width,
					$target_num, $options);
	}
    }

    function reset( $formatstring, $bar, $prefill, $width, $target_num, $options) {
	if ( $this->_active) parent::reset( $formatstring, $bar,
					    $prefill, $width,
					    $target_num, $options);
    }
    function update( $current) {
	if ( $this->_active) parent::update( $current);
    }
    function display( $current) {
	if ( $this->_active) parent::display( $current);
    }
    function erase($clear = false) {
	if ( $this->_active) parent::erase( $clear);
    }
}

