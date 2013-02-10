<?php

class UserController extends Controller
{

	public function filters()
	{
		return array();
	}

	/**
	 * Display account information.
	 *
	 * @param string $username the username.
	 */
	public function actionView($username)
	{
		$this->checkAuth();
		$this->allow(array('GET', 'PUT'));
		$this->sendResponse(array(
			'user'=>$username
		));
		// Forbidden
		$this->sendResponse(403);
	}

	/**
	 * Update an account.
	 *
	 * @param string $username the username.
	 */
	public function actionUpdate($username)
	{
		$this->checkAuth();
		$this->allow(array('GET', 'PUT'));
		$data = $this->getPutData();
		// Forbidden
		if (isset($data['user']))
			return $this->sendResponse(403);

		$this->sendResponse(array(
			'user'=>$username
		));
	}
}