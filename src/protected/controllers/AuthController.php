<?php

/**
 * Handles user authentication and initial token generation
 */
class AuthController extends Controller
{

	/**
	 * Authenticates a user and responds with a token that should be used in 
	 * further communication.
	 * @throws CHttpException if the supplied credentials are invalid
	 */
	public function actionLogin()
	{
		// TODO: Use filters instead of allow()
		$this->allow('POST');
		$this->validate('auth.login', $this->decodedJsonData);

		// Authenticate before doing anything else
		$username = $this->decodedJsonData->username;
		$password = $this->decodedJsonData->password;
		$authProvider = Yii::app()->authProvider;

		if ($authProvider->authenticate($username, $password))
		{
			// Create a user account if this is the first time the user logs in
			$user = User::model()->findByAttributes(array(
				'username' => $this->decodedJsonData->username));

			if ($user === null)
			{
				// TODO: Determine role
				$user = new User();
				$user->username = $username;
				$user->balance = 0.00;
				$user->role = 1;
				$user->save();
			}

			// TODO: Re-use valid tokens
			$token = new UserToken;
			$token->user_id = $user->id;
			$token->save();

			$this->sendResponse(201, array(
				'token' => $token->token,
				'expires' => $token->expires,
			));
		}

		throw new CHttpException(403, 'Invalid credentials');
	}

}
