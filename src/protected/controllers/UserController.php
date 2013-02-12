<?php

/**
 * Description of UserController
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class UserController extends Controller
{

	/**
	 * @return array the filters for this controller
	 */
	public function filters()
	{
		return array_merge(parent::filters(), array(
			array(
				'RestrictHttpMethodsFilter + view, update',
				'methods' => array('GET', 'PUT'),
			),
			array(
				'RestrictHttpMethodsFilter + getBalance',
				'methods' => array('GET'),
			),
		));
	}
	
	/**
	 * Returns the user's balance
	 * @throws CHttpException if the user cannot be found for some reason 
	 * (almost theoretically impossible to reach that code)
	 */
	public function actionGetBalance()
	{
		$user = User::model()->findByToken($this->token);

		if ($user !== null)
		{
			$this->sendResponse(array(
				'balance'=>$user->balance,
			));
		}

		throw new CHttpException(404, 'Could not find user');
	}
	
	/**
	 * Display account information.
	 *
	 * @param string $username the username.
	 */
	public function actionView($username)
	{
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
		$data = $this->getPutData();
		// Forbidden
		if (isset($data['user']))
			return $this->sendResponse(403);

		$this->sendResponse(array(
			'user'=>$username
		));
	}
}
