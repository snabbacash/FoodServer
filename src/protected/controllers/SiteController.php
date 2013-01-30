<?php

class SiteController extends Controller
{

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if (($error = Yii::app()->errorHandler->error))
		{
			$object = new stdClass();
			$object->status = false;
			$object->message = $error['message'];

			echo CJSON::encode($object);
			exit;
		}
	}

}