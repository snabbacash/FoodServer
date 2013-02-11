<?php

class AuthController extends Controller
{
	public function filters()
	{
		return array();
	}

	public function actionLogin()
	{
		$this->allow('POST');
		$this->validate('auth.login', (object) $_POST);
		// @TODO negge, password should not be passed along further
		unset($_POST['password']);

		if (0 === count($user = User::model()->findByAttributes($_POST)))
			$this->sendResponse(403);
		// else create user, how should this work?

		$token = new UserToken;
		$token->user_id = $user->id;
		$token->save();

		$this->sendResponse(201, array(
			'token'=>$token->token,
			'expires'=>$token->expires,
		));
	}
}

