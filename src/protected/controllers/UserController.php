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
		return array_merge(array(
			array(
				'RestrictHttpMethodsFilter + view, update',
				'methods' => array('GET', 'PUT'),
			),
			array(
				'RestrictHttpMethodsFilter + info',
				'methods' => array('GET'),
			),
		), parent::filters());
	}
	
	/**
	 * Returns details about the user
	 * @throws CHttpException if the user can't be found
	 */
	public function actionInfo()
	{
		$user = User::model()->findByToken($this->token);

		if ($user !== null)
		{
			$this->sendResponse(array(
				'username'=>$user->username,
				'name'=>$user->name,
				'balance'=>(double) $user->balance,
				'role'=>$user->role->name
			));
		}

		throw new CHttpException(404, 'Unknown user');
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
