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
		if (isset($_POST['username'])) {
			$this->sendResponse(201, array(
				'token'=>''
			));
		}
		$this->sendResponse(403);
	}
}

