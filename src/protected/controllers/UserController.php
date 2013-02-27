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

		$requestUser = User::model()->findByToken($this->token);
		$user = User::model()->findByAttributes(array('username'=>$username));
		$data = $this->decodedJsonData;

		if(isset($data->balance)){
			if ($requestUser->role->name == "amica"){
				$amount = $data->balance;
				if( $amount<0 && $user->balance < (-$amount)){
					return $this->sendResponse(403, "Not enough funds");
				}
				$transaction = new Transaction();
	            $transaction->amount = $amount;
	            $transaction->user = $user->id;
	            $transaction->timestamp = time();


	            $currentBalance = $user->balance;
	            $user->balance = $currentBalance+$amount;

	            if(!$transaction->save ||!$user->save()){
	            	return $this->sendResponse(500, "Shit went bananas");
	            }

			} else {
				return $this->sendResponse(403, "Only Kassatanter is allowed to update balance");
			}
		}
		$this->sendResponse($user);
	}


}
