<?php

 
/**

*/
class Form_First 
{
	protected $protecteds = array('out' => '');

	private $privates = array();
	public $publics = array();
	
	function __CONSTRUCT($checked) {
		
		$innerHTML = '<br><br><hr>' . $checked['fname'] . ' ' . $checked['lname']	. ' ' . date("Y-m-d")  . '<br>' . $checked['comment'];
		$response = array('tag' => 'asis', 'innerHTML' => $innerHTML );
		
		$this->protecteds['out'] = json_encode($response);
	
	}

		/**
	*	
	*/
	public function getOut()
	{
		return $this->protecteds['out'];
	}
	
}