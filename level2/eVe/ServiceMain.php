<?php
namespace Services;
use Services\Eve;
use Services\Pages;
use Services\Pages\PageFirst;


class ServiceMain extends Eve 
{
	private $confidentials = array();
	
	private $privates = array(
		'objectName' => ''
		,  'parentObjectName' => ''		
		, 'argsRequired' => array() 
		,  'permissionsRequired' => array('all' => array(), 'in' => array()
			, 'excludeds' => array())
		, 'metaIn' => array()
		, 'dataIn' => array()
		, 'metaOut' => array()
		, 'dataOut' => array()
	);
	
	// $permissionsRequired is the same as parent class (eVe)
	
	protected $shipment, $requestProperties;

	/** 
		$passport = array(
				'caller' => array(
					'userId' => ''
					, 'userName' => ''
					, 'privileges' => array()
				)
				, 'approvedService' => array()
			);
	*/		
	protected function child1($request)
	{
		$passport = $request->getPassport();
		if(empty($passport)) {
			$this->protecteds['eVeStatus'] = 'unsafe';
			$this->securityFailed();
			return false;
		}
		
// 
var_dump('<pre>Passport-39:', $request->getPassport()); 
exit('<br>ServiceMain.php exit 49');
		
		if(!$starterClass = $passport['approvedService']['starterClass']) {
			$this->protecteds['toDeveloper']['Missing starterClass'] = $passport['approvedService']['serviceName'];
			
			return false;
		}
		
		$params = array(
			'objectName' => 'starter'
			,  'parentObjectName' => 'N_A'
		);

		if(self::OP == 'Windows') {
			$starterClass = str_replace('/', '\\', $starterClass);
		}
		
		$this->starter =  new $starterClass($request, $params);

// var_dump('<pre>Passport-33:', $request->getPassport()); 
return;
exit('<br>exit 28');
		

		// R***
		if(empty($request['metam'])) {
			$this->metadata['toDeveloper']['formFields'] = 'Cannot get formFields from database. e66: ' . mysql_error();
			$this->addtoCaller();
			return false;
		}
		
		$request = $this->checkrequestBasics($request);
		if(!$request || !$this->checkPermissions())
		{
			$this->status = 'exit';
			$this->securityFailed();
			return false;
		}
		
// echo '<br>ProcessingRequest---objectName: ' . $this->privates['objectName'];  exit;
// var_dump('<pre>ProcessingRequest---privates:', $this->privates);  exit;

			// The value on the right array will be lost if the left array has the same key.
			$this->metam = $this->metam + $request['metam'];
			$this->metam['processorClass'] = $this->metam['requestName'];
			
// var_dump('<pre>this:', $this); exit;
		
		//R*** means remove
		$this->metam['toCaller']['test'] = 'Testing $toCaller messages';	
	
		// step 1- processing request
		$processorFile = DOCROOT . '/31_Process/' . $this->metam['moduleName'] .  '/' .  $this->metam['processorClass'] . '.php';
		require_once($processorFile);
		$requestrequest['metam'] = $this->metam;
		

	
		$requestrequest['privates']['objectName'] = 'request';
		$requestrequest['privates']['parentObjectName'] = $this->privates['objectName'];
		
		// R***   testing
		$request['datam']['loginname'] = 'kevinhy';
 
		$request = new $requestrequest['metam']['processorClass']($requestrequest);

// exit('<br>exit 68');
	
		// The value on the right array will be lost if the left array has the same key.
		$requestProperties = $this->mergeKid($request);
		// var_dump('<pre>72:', $requestProperties); exit;		
		 // echo '<br>69---------: ' . htmlspecialchars($requestProperties['metam']['httpHeader']);  exit;
 
		// The value on the right array will be lost if the left array has the same key.
		$this->metam = $this->metam + $requestProperties['metam'];
		$this->datam = $requestProperties['datam'];


		
		// echo '<br>this->63 metam[formatType]: ' . $this->requestProperties['metam']['formatType'];

// exit('<br>exit 83');
		
		unset($request);
		unset($requestrequest);
	
		// $this->requestProperties survives
				

		// step 2- Formatting Response / shipment
		if(empty($requestProperties['status']) || $requestProperties['status'] != 'ok') {
			$this->metam['toCaller'][] = array('Error', 'Sorry an error occured in the system. 12.');	
			$this->metam['toDeveloper'][] = array('processRequest', 'No status was returned or status is not ok. 16.');
			$this->status = 'Failed';
exit('<br>exit 96');			
		}
		else {

			// @toDO : before formatting for modules interferance
			$this->beforeFormatting();
			$requestProperties = array();	
// exit('<br>exit 102');
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

