<?php

/**
 * Description of LDAPAuthenticationProvider
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class LDAPAuthenticationProvider extends CApplicationComponent implements IAuthenticationProvider
{

	public $ldapUrl;
	
	public $ldapSearchBase;
	
	private $_handle;
	
	private $_ldapEntries = array();
	
	/**
	 * Initializes the component
	 * @throws CHttpException if configuration parameters are missing
	 */
	public function init()
	{
		parent::init();

		// Sanity checks
		if (!isset($this->ldapUrl) || !isset($this->ldapSearchBase))
			throw new CHttpException(500, 'Authentication provider has not been configured properly');
	}

	public function authenticate($username, $password)
	{
		// Connect to the LDAP server
		$this->_handle = @ldap_connect($this->ldapUrl);
		if ($this->_handle === false)
			throw new CHttpException(500, 'Could not connect to LDAP server');

		return ldap_bind($this->_handle, 'uid='.$username.','.$this->ldapSearchBase, $password);
	}

	public function getRoles()
	{
		
	}

}