<?php

/**
 * Description of UserController
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class UserController extends Controller
{

	public function actionAuthenticate($username, $password)
	{
		if (Yii::app()->authProvider->authenticate($username, $password))
			die("Authenticated successfully");
		else
			die("Invalid credentials");
	}

}