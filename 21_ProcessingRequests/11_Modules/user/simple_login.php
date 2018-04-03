<?php
/**
*	@name			:	authenticate.php
*	@tips			:	Authentication is done in securityCheck()
*/


require_once(DOCROOT . '/21_ProcessingRequests/_ProcessRequest.php');

/**
*	@description : A typical class for a form element
*	Properties will include $this->datam['html'], $this->datam['addJs']
*	
*/
class simpleLogin extends processRequest 
{
	
	// $permissionsRequired same as parent class
	
	protected $request = '';
	protected $14_Tasks = array();

	/**
	* 	
	*/
	public function form_display() 
	{
		// simple forms do not have $form objects
		// $form = array();
		
		$html = '<div id="div_simpleLogin" style="margin:2em; position: relative; float: right; " > <form name="simpleLogin" id="simpleLogin" action="#" method="POST">
		<input name="loginname" size="10"> <input type="submit" value="Login" class="formsubmit"> <input type="password" name="password" size="10"></form></div>';
		
		// getting validation criteria (pregCheck_simpleLogin) for javascript
		$processer = 'form_process_simpleLogin';
		require_once(DOCROOT . '/11_SecurityChecks/Requests/' . $processer . '.php');
		unset($pregCheckPost['token']);
		$pregCheckPostJson = json_encode($pregCheckPost);
		// TRICK for Javascript: string is not wrapped with quote like ' ...string... ' So, pregCheck_simpleLogin is assoc array, not a string. No need to JSON.parse
		$addJs = array();
		$addJs[] = " var pregCheck_simpleLogin = " . $pregCheckPostJson . "; ";
		
		// getting token
		require_once(DOCROOT . '/1_Backbone/45_Tools/formFunctions.php');
		// expires in 600 seconds
		$token_simpleLogin = generateToken('token_simpleLogin', 600);
		//R***  echo 'token_simpleLogin: ' . $token_simpleLogin;  exit;
		$addJs[] = " var token_simpleLogin = '" . $token_simpleLogin . "'; ";
		
		$this->datam['html'] = $html;
		
		if(empty($this->datam['addJs'])) {
			$this->datam['addJs'] = $addJs;	
		}
		else {
			$this->datam['addJs'] = $this->datam['addJs'] + $addJs;
		}
		
		$this->status = 'ok';
		
	}

	/**
		* 	add javascript to control all events including clicking links
	*/
	public function form_process() 
	{
		
		$this->datam = '<div style="background-color:beige; height:5em; width:400px; padding:2em;"><h3>@toDo : task : login</h3></div>';
	
	}


	
	protected function runTask($task)
	{
		
	}
	
}
