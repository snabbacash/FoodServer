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
	
	private $_filter;
	
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
		
		$this->_filter = 'uid='.$username;
		
		return @ldap_bind($this->_handle, $this->_filter.','.$this->ldapSearchBase, $password);
	}

	public function getRoles()
	{
		if ($this->_handle === null)
			throw new CHttpException(500, 'Not connected to LDAP');

		$roles = array();
		$attributes = array('arcadaRole');
		$result = ldap_search($this->_handle, $this->ldapSearchBase, $this->_filter, $attributes);

		if (ldap_count_entries($this->_handle, $result) === 1)
		{
			$entries = ldap_get_entries($this->_handle, $result);

			foreach ($entries[0]['arcadarole'] as $key => $role)
				if (is_int($key))
					$roles[] = $role;
		}
		
		if (empty($roles))
			throw new CHttpException(500, 'No roles found for "' . $this->_filter . '"');
		
		return $roles;
	}

}