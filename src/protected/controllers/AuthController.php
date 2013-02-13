<?php

/**
 * Handles user authentication and initial token generation
 */
class AuthController extends Controller
{

	/**
	 * Overriden from Controller because we can't check for the token here
	 * @return array the filters for this controller
	 */
	public function filters()
	{
		return array(
			array(
				'RestrictHttpMethodsFilter',
				'methods'=>'POST',
			),
			'decodeJsonPostData',
		);
	}

	/**
	 * Authenticates a user and responds with a token that should be used in 
	 * further communication.
	 * @throws CHttpException if the supplied credentials are invalid
	 */
	public function actionLogin()
	{
		$this->validate('auth.login', $this->decodedJsonData);
		// Authenticate before doing anything else
		$username = $this->decodedJsonData->user;
		$password = $this->decodedJsonData->pass;
		
		/* @var $authProvider IAuthenticationProvider */
		$authProvider = Yii::app()->authProvider;

		if ($authProvider->authenticate($username, $password))
		{
			// Create a user account if this is the first time the user logs in
			$user = User::model()->findByAttributes(array(
				'username'=>$username));

			if ($user === null)
			{
				// Get the user role and name
				$role = $authProvider->getRole();
				$name = $authProvider->getName();
				
				// Create the user
				$user = new User();
				$user->username = $username;
				$user->name = $name;
				$user->role_id = $role->id;
				
				if (!$user->save())
					throw new CHttpException(500, 'Unable to create user');
			}
			
			// Re-use existing tokens when they expire
			if ($user->token !== null)
			{
				$token = $user->token;

				// Generate a new token if the current one has expired
				if (!$token->isValid())
				{
					$token->token = $token->generateToken();
					
					// MySQL time seems to not be the same as PHP time
					$token->created = date("Y-m-d H:i:s");
					$token->save();
				}
			}
			else
			{
				// Create a new token and associate it with the user
				$token = new UserToken();
				$token->user_id = $user->id;
				$token->token = $token->generateToken();
				$token->save();
			}
			
			// Send the token
			$this->sendResponse(201, array('token' => $token->token));
		}

		throw new CHttpException(403, 'Invalid credentials');
	}

}
