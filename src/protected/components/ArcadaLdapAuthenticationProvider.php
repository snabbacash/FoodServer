<?php

/**
 * Provides authentication to Arcada's LDAP.
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class ArcadaLdapAuthenticationProvider extends CApplicationComponent implements IAuthenticationProvider
{

	/**
	 * @var string the URL to the LDAP server
	 */
	public $ldapUrl;
	
	/**
	 * @var string the search base
	 */
	public $ldapSearchBase;
	
	/**
	 * @var the LDAP link identifier
	 */
	private $_handle;

	/**
	 * @var string the filter used for searching for users
	 */
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

	/**
	 * Authenticates the user using the given credentials
	 * @param string $username
	 * @param string $password
	 * @return boolean
	 * @throws CHttpException
	 */
	public function authenticate($username, $password)
	{
		// Connect to the LDAP server
		$this->_handle = @ldap_connect($this->ldapUrl);
		if ($this->_handle === false)
			throw new CHttpException(500, 'Could not connect to LDAP server');
		
		$this->_filter = 'uid='.$username;
		
		return @ldap_bind($this->_handle, $this->_filter.','.$this->ldapSearchBase, $password);
	}

	/**
	 * Returns the role that the user should have
	 * @return UserRole
	 */
	public function getRole()
	{
		$arcadaRoles = $this->getArcadaRoles();
		$role = UserRole::ROLE_OTHER;

		foreach ($arcadaRoles as $arcadaRole)
		{
			if ($arcadaRole == 'employee')
			{
				$role = UserRole::ROLE_STAFF;
				break;
			}
			elseif ($arcadaRole == 'student')
			{
				$role = UserRole::ROLE_STUDENT;
				break;
			}
		}

		return UserRole::model()->findByAttributes(array('name' => $role));
	}

	/**
	 * Returns the arcadaRoles for the currently authenticated user
	 * @return array the roles
	 * @throws CHttpException if no roles were found
	 */
	private function getArcadaRoles()
	{
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