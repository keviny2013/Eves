<?php
/**
*	@name			:	Formatting.php
*	@owner			: 	eVe
*	@description	:	manages, routes different fromatting options
*	@process 		: 	
*	@tips			:	 
*	@rules			:	There is a beforeFormatting() function in Adam class. 
					beforeFormatting() maybe used to override elements, blocks and datam before they are formatted.
*	@conventions	:	
*/


class FormattingResponse extends eVe 
{
	protected $privates = array('objectName' => 'kevinxxx',  'parentObjectName' => '',  'permissionsRequired' => array('all' => array(), 'in' => array(), 'notin' => array()), 'paramsRequired' => array('objectName', 'parentObject') );

	public function __CONSTRUCT($params)
	{

		$metam = $params['metam'];
		$params = $this->checkParamsBasics($params);
		if(!$params || !$this->checkPermissions())
		{
			$this->status = 'exit';
			$this->securityFailed();
			return false;
		}
			 
		// $metam cannot override $this->metam
		$this->metam = $this->metam + $metam;
		unset($metam);
	
		$this->formatResponse($this->metam, $params['datam']);

		
			if($this->shipment && !$this->status) {
				$this->status = 'ok';
				// All objects have to return $this->datam to be consistent!
				
				$this->datam = $this->shipment;
				
			}
			
			elseif(!$this->status) {
				$this->metam['toCaller'][] = array('Error', 'Sorry an error occured in the system. 12.');	
				$this->metam['toDeveloper'][] = array('formatResponse', 'No status was returned or status is not ok. 19.');
			}
			
		return true;
		
	}

	
		
	/**
	*	This method coordinates files to render / format $datam using $params
	*   
	*	if no error $this->shipment will be set
	*/
	protected function formatResponse($metam, $datam) 
	{
		
		$formatterFile = DOCROOT . '/31_FormattingResponses/17_Tools/formatResponse_' . $metam['formatType'] . '.php';
		
		// 	echo '<br>$formatterFile: ' . $formatterFile; exit;
		
		if(!file_exists($formatterFile)) {

			$this->status = 'Missing file';
			$this->metam['toDeveloper']['formatResponse'] = 'File to formatResponse does not exists. 46: ' . $formatterFile;		
			$this->metam['toCaller']['Formatting Response'] = array('Error', 'Sorry an error occured in the system. 19.');			
		}
		else {
			
			/**
			* 	all formatterFiles must generate $this->shipment package
			*	formatterFiles have access to $metam, $datam and $this
			*/
			// $this->datam
			require_once($formatterFile);
			

		}
	
		
	}	
	
	
}


