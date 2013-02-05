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
		$this->sendResponse(201, array(
			'token'=>''
		));

		$this->sendResponse(403);
	}
}

