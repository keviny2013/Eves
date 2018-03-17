<?php

namespace eVe;
use eVe\eVe;

/*
@name			:	securityCheck.php  
@description	:	class securityCheck is fundamental first step for application

@toDo : Encryption needs improvement
	Currently:
	writing given $string to a temp file (temp_toDeveloper)
	encrypting temp_toDeveloper file
	Writing encrypted string to 1 new file each time
	Then, deleting temp_toDeveloper file
*/

class Encrypt3 extends eVe
{
	private const FILE_ENCRYPTION_BLOCKS = 10000;
	private $confidentials = array();
	private $privates = array(
		'objectName' => ''
		,  'parentObjectName' => ''	
		,  'child1Args' => array()
		,  'permissionsRequired' => array()
			
		, 'argsRequired' => array(
			   'objectName' => array(
					'method' => 'v_FileName'
					, 'pattern' => ''
				)
			   , 'parentObjectName' => array(
					'method' => 'v_FileName'
					, 'pattern' => ''
				)
				, 'child1Args' => array(
					'method' => 'v_Encrypt3'
					, 'pattern' => ''
				)
			)
		,  'argsOptional' => array(
					'parentObjectName' => array(
						'method' => 'v_FileName'
						, 'pattern' => ''
					)
				)	
		,  'permissionsRequired' => array()

		, 'metaIn' => array()
		, 'dataIn' => array()
		, 'metaOut' => array()
		, 'dataOut' => array()
		, 'startingTime' => 93
		, 'endingTime' => 0		
	);
	

	protected function child1() {

		if(empty($this->lastPrivates['child1Args'])) {
			$this->operationFailed('Missing encyption arguments', '');
			return false;
		}
		
		$child1Args = $this->lastPrivates['child1Args'];
		
		$string = $child1Args['dataIn'] ;
		$fileName = $child1Args['metaIn']['fileName'];
		$key = $child1Args['metaIn']['logKey'];
		$encrypted = $this->encryptFile($fileName, $key, $string);
		if($encrypted) {
			$this->lastPrivates['metaOut']['status'] = 'Success';
			$this->protecteds['eVeTracks'][] = 'Encrypted ' . $fileName;
		}
		else {
			$this->lastPrivates['metaOut']['status'] = 'Failed';
			$this->protecteds['eVeTracks'][] = 'Encrypting file ' . $fileName . ' failed.';
		}
	
		return true;
		
	}
			

	/**
		Encrypts $string 
			and adds to file: {$fileName}_encrypted
		Uses folder: self::DOCROOT . 'Common/Logs/'
	*/
	function encryptFile($fileName, $key, $string)
	{
		$temp_fileName = self::DOCROOT . 'Common/Logs/temp_' . $fileName;
		$write = file_put_contents($temp_fileName, $string);
		if(!$write) {
			$this->protecteds['fatalIssue'] = 'Failed';
			$this->protecteds['eVeTracks'][] = 'File write failed.'. $temp_fileName;
			$this->lastPrivates['metaOut']['status'] = 'Failed';
			$this->lastPrivates['dataOut'] = '';
			return false;
		}

		$dest = self::DOCROOT . 'Common/Logs/' . $fileName . '_encrypted_' . date('U');		
		$key = substr(sha1($key, true), 0, 16);
		$iv = openssl_random_pseudo_bytes(16);
		
		$error = false;
		if ($fpOut = fopen($dest, 'a')) {
			// Put the initialization vector to the beginning of the file
			fwrite($fpOut, $iv);

			if ($fpIn = fopen($temp_fileName, 'rb')) {
								
				while (!feof($fpIn)) {
					$plaintext = fread($fpIn, 16 * self::FILE_ENCRYPTION_BLOCKS);
					$ciphertext = openssl_encrypt($plaintext, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
					// Use the first 16 bytes of the ciphertext as the next initialization vector
					$iv = substr($ciphertext, 0, 16);
					fwrite($fpOut, $ciphertext);
				}
				fclose($fpIn);
				
			} else {
				$error = true;
			}
			
			fclose($fpOut);
		} else {
			$error = true;
		}

		unlink($temp_fileName);
	
		return $error ? false : $dest;
	}
	
	// IMPORTANT: copy this to each child class
	protected function set_lastPrivates() 
	{
		$this->lastPrivates = $this->privates;
		return;
	}

	// IMPORTANT: copy this to each child class	
	protected function set_privates($params) 
	{
		if(empty($params)) {
			return true;
		}
		
		foreach($this->lastPrivates['argsRequired'] as $key => $val) {
			if(isset($params[$key])) {
				$this->lastPrivates[$key] = $params[$key];
			}
		}
		foreach($this->lastPrivates['argsOptional'] as $key2 => $val2) {
			if(isset($params[$key2])) {
				$this->lastPrivates[$key2] = $params[$key2];
			}
		}
		
		$this->privates = $this->lastPrivates;
		
		return true;
		
	}


	
}  
