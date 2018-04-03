<?php
/**
*	@name			:	authenticate.php
*	@tips			:	Authentication is done in securityCheck()
*/


require_once(DOCROOT . '/21_ProcessingRequests/_ProcessRequest.php');

class form_process_simpleLogin extends processRequest {
	
	protected $request = '';
	protected $14_Tasks = array();

	public function __CONSTRUCT($params)
	{
		parent::__Construct($params);
		
		$this->request = $params['request'];
		
		$14_Tasks = array('a', 'b', 'c');
		$num14_Tasks = count($14_Tasks);
		for($i = 0; $i < $num14_Tasks; $i++) {
			$this->runTask($14_Tasks[$i]);
		}
		
		$this->metam = $params;
		// @toDo :  login
		
		/*
		$this->metam['templates'] = array(DOCROOT . '/31_FormattingResponses/firstPage/templates/httpHeader.php', 
	DOCROOT . '/31_FormattingResponses/firstPage/templates/htmlBody.php', DOCROOT . '/31_FormattingResponses/firstPage/templates/firstPageBody.php', 
	DOCROOT . '/31_FormattingResponses/firstPage/templates/htmlFooter.php', );
	
	$this->datam['title'] = 'Another story';	
		*/
		
	$this->datam = '<div style="background-color:beige; height:5em; width:400px; padding:2em;"><h3>@toDo : task : login</h3></div>';
		

		$this->status = 'ok';

	
		return true;	
	
	}

	
	protected function runTask($task)
	{
		
	}
	
}
