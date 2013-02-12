<?php

/**
 * Interface for authentication providers
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
interface IAuthenticationProvider
{

	/**
	 * Authenticates a user
	 * @param string $username
	 * @param string $password
	 * @return boolean
	 */
	public function authenticate($username, $password);

	/**
	 * Returns the real name of the user
	 * @return string the name
	 */
	public function getName();
	
	/**
	 * Returns the role that the user should have
	 * @return UserRole
	 */
	public function getRole();
}