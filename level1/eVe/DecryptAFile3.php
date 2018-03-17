<?php

namespace eVe;
use eVe\eVe;

/*
@name			:	securityCheck.php  
@description	:	class securityCheck is fundamental first step for application

@toDo : Encryption needs improvement

*/

/**
  'permissionsRequired' => array(
				'requiredAll' => array('group' => 'developer')
				, 'in' => array('ip' => '127.0.0.0')
				, 'notin' => array()
			)
*/
class DecryptAFile3 extends eVe
{
	private const FILE_ENCRYPTION_BLOCKS = 10000;
	private $confidentials = array();
	private $privates = array(
		'child1Args' => array()
		, 'argsRequired' => array(
			   'objectName' => array(
					'method' => 'v_FileName'
					, 'pattern' => ''
				)
				, 'child1Args' => array(
					'method' => 'v_decrypt3'
					, 'pattern' => ''
				)
			)
		,  'argsOptional' => array(
					'parentObjectName' => array(
						'method' => 'v_FileName'
						, 'pattern' => ''
					)
				)
		, 'startingTime' => 94
		, 'endingTime' => 0
		, 'objectName' => ''
		, 'parentObjectName' => ''		

		,  'permissionsRequired' => array()

		, 'metaIn' => array()
		, 'dataIn' => array()
		, 'metaOut' => array()
		, 'dataOut' => array()
	);
	

	protected function child1() {
		if(empty($this->lastPrivates['child1Args'])) {
			$this->operationFailed('Missing decyption arguments', '');			
			return false;
		}
		
		$child1Args = $this->lastPrivates['child1Args'];
				
		$fileName = $child1Args['sourceFile'];
		$key = $child1Args['key'];

		$decrypted = $this->decryptFile($fileName, $key);

		if($decrypted) {
			$this->privates['metaOut']['status'] = 'success';
			$this->protecteds['eVeTracks'][] = 'Decrypted.';
		}
		else {
			$this->protecteds['fatalIssue'] = 'Failed';
			$this->protecteds['eVeTracks'][] = 'Decryption failed.';
			$this->privates['metaOut']['status'] = 'failed';
			
		}
		
	}


	function decryptFile($fileName, $key)
	{
		$sourceFile = self::DOCROOT . 'Common/Logs/' . $fileName;
		if(!file_exists($sourceFile)) {
			$this->protecteds['eVeTracks'][] = 'sourceFile file does not exist.';
			return false;
		}	
		$dest = self::DOCROOT . 'Common/Logs/' . $fileName . '_decrypted';

		if(!$key) {
			$key = self::CONFIDENTIALS['logKey'];
		}
		$key = substr(sha1($key, true), 0, 16);
		$iv = openssl_random_pseudo_bytes(16);
		
		$error = false;
		if ($fpOut = fopen($dest, 'w')) {			
			if ($fpIn = fopen($sourceFile, 'rb')) {
				// Get the initialization vector from the beginning of the file
				$iv = fread($fpIn, 16);
				while (!feof($fpIn)) {
					// we have to read one block more for decrypting than for encrypting
					$ciphertext = fread($fpIn, 16 * (self::FILE_ENCRYPTION_BLOCKS + 1)); 
					$plaintext = openssl_decrypt($ciphertext, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
					// Use the first 16 bytes of the ciphertext as the next initialization vector
					$iv = substr($ciphertext, 0, 16);
					$write = fwrite($fpOut, $plaintext);
				}
				fclose($fpIn);
			} else {
				$error = 1;
			}
			fclose($fpOut);
		} else {
			$error = 1;
		}
		return $error ? false : $dest;
	}

	
	// IMPORTANT: copy this to each child class
	protected function set_lastPrivates() {
		$this->lastPrivates = $this->privates;
		return;
	}

	// IMPORTANT: copy this to each child class	
	protected function set_privates($params) {
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



