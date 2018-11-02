<?php

abstract class BaseWuyeReport {
	
	
	private $_delegate;
	
	
	public function __construct(){
		
	}
	
	
	public function setDelegate($delegate){
		$this->_delegate = $delegate;
	}
}