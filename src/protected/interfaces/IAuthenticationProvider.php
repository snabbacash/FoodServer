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
	 * Returns the roles the user has
	 * @return array the various roles the user has
	 */
	public function getRoles();
}