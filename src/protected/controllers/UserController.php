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
				'RestrictHttpMethodsFilter + index',
				'methods' => array('GET'),
			),
		), parent::filters());
	}
	
	/**
	 * Returns details about the user
	 * @throws CHttpException if the user can't be found
	 */
	public function actionIndex()
	{
		$user = User::model()->findByToken($this->token);

		return $this->actionView($user->username);
	}
	
	/**
	 * Display account information.
	 *
	 * @param string $username the username.
	 */
	public function actionView($username)
	{
		$user = User::model()->findByAttributes(array('username'=>$username));

		if ($user !== null)
			$this->sendResponse($user);

		throw new CHttpException(404, 'Unknown user');
	}

	/**
	 * Update an account.
	 *
	 * @param string $username the username.
	 */
	public function actionUpdate($username)
	{
		$user = User::model()->findByAttributes(array('username'=>$username));
		$data = $this->getPutData();
		// Forbidden
		if (isset($data['user']))
			return $this->sendResponse(403);

		$this->sendResponse($user);
	}
}
