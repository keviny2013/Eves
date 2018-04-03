<?php
/**
*	@name			:	ProcessingRequest.php
*	@owner			: 	eVe
*	@description	:	handles the request after the security check
*	@process 		: 	
*	@tips			:	 
*	@rules			:	All objects and variables must be children of $ProcessingRequest
*	@conventions	:	name = machine name, title = humanly name
*
*/


class ProcessingRequest extends eVe 
{
	// $permissionsRequired same as parent class
	
	protected $shipment, $requestProperties;
	protected $privates = array('objectName' => '',  'parentObjectName' => '',  'permissionsRequired' => array('all' => array(), 'in' => array(), 'notin' => array()), 'paramsRequired' => array('objectName', 'parentObject') );

	
	// public function __CONSTRUCT($params, $params['datam'])
	public function __CONSTRUCT($params)
	{
// var_dump('<pre>ProcessingRequest:', $params['privates']); exit;		

		// R***
		if(empty($params['metam'])) {
			$this->metadata['toDeveloper']['formFields'] = 'Cannot get formFields from database. e66: ' . mysql_error();
			$this->addtoCaller();
			return false;
		}
		
		$params = $this->checkParamsBasics($params);
		if(!$params || !$this->checkPermissions())
		{
			$this->status = 'exit';
			$this->securityFailed();
			return false;
		}
		
// echo '<br>ProcessingRequest---objectName: ' . $this->privates['objectName'];  exit;
// var_dump('<pre>ProcessingRequest---privates:', $this->privates);  exit;

			// $this->metam  cannot be overridden
			$this->metam = $this->metam + $params['metam'];
			$this->metam['processorClass'] = $this->metam['requestName'];
			
// var_dump('<pre>this:', $this); exit;
		
		//R*** means remove
		$this->metam['toCaller']['test'] = 'Testing $toCaller messages';	
	
		// step 1- processing request
		$processorFile = DOCROOT . '/21_ProcessingRequests/11_Modules/' . $this->metam['moduleName'] .  '/' .  $this->metam['processorClass'] . '.php';
		require_once($processorFile);
		$requestParams['metam'] = $this->metam;
		

	
		$requestParams['privates']['objectName'] = 'request';
		$requestParams['privates']['parentObjectName'] = $this->privates['objectName'];
		
		// R***   testing
		$params['datam']['loginname'] = 'kevinhy';
 
		$request = new $requestParams['metam']['processorClass']($requestParams);

		// Overrides existing message if has the same key.
		$requestProperties = $this->mergeKid($request);
// var_dump('<pre>72:', $requestProperties); exit;		
 // echo '<br>69---------: ' . htmlspecialchars($requestProperties['metam']['httpHeader']);  exit;
 
		// Now $this->metam  cannot be overridden
		$this->metam = $this->metam + $requestProperties['metam'];
		$this->datam = $requestProperties['datam'];


		
		// echo '<br>this->63 metam[formatType]: ' . $this->requestProperties['metam']['formatType'];


		
		unset($request);
		unset($requestParams);
	
		// $this->requestProperties survives
				

		// step 2- Formatting Response / shipment
		if(empty($requestProperties['status']) || $requestProperties['status'] != 'ok') {
			$this->metam['toCaller'][] = array('Error', 'Sorry an error occured in the system. 12.');	
			$this->metam['toDeveloper'][] = array('processRequest', 'No status was returned or status is not ok. 16.');
			$this->status = 'Failed';
		}
		else {

			// @toDO : before formatting for modules interferance
			$this->beforeFormatting();
			$requestProperties = array();	

		}
		
		if(!$this->status) {
			$this->status = 'ok';
		}
		
		return true;	
	
	}
	
	
	/**
	*	@toDO : modules can customize this method
		See how page_first_renderHtmlBody.php loops through $this object 
			Then you can figure out region > block > element > kids
			and manipulate the one that you want
				Also you can use foeach loop or var_dump to analyize $this: 
					var_dump('<pre>metam: ', $requestProperties['metam']);  exit;
					var_dump('<pre>datam: ', $requestProperties['datam']['regions']);	exit;
	
	*	
	*/
	protected function beforeFormatting() 
	{
		// writing $toCaller messages to the beginning of  2nd row, 2nd region as 1st block
		$toCallerStr = writeMessages($this->metam['toCaller'], 'System Messages');
		$thisBlock = array();
		$thisBlock[0][0] = array('htmltag' => 'asis');
		$thisBlock[0]['asis'] = $toCallerStr;

		// R*** array_unshift($this->datam['regions'][1][0][0][0], $thisBlock);

	}
	

}


//////////////////////////////////////////////////////////////////////////////////

