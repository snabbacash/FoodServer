<?php

class SiteController extends Controller
{

	/**
	 * Override parent implementation so the application can reach site/error 
	 * even when an exception is thrown before the action is called (e.g. in 
	 * a filter)
	 * @return array the filters for this controllers
	 */
	public function filters()
	{
		return array();
	}

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