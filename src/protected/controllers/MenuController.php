<?php

class MenuController extends Controller
{
	
	/**
	 * @return array the filters for this controller
	 */
	public function filters()
	{
		return array_merge(array(
			array(
				'RestrictHttpMethodsFilter + list, view',
				'methods'=>'GET',
			),
		), parent::filters());
	}

	/**
	 * Display the menu for a specific week
	 *
	 * @param int $week the week number 1-52.
	 */
	public function actionList($week)
	{
		if ($week >= 1 && $week <= 52) {
			$this->sendResponse(array(
				'week' => $week
			));
		}
		$this->sendResponse(404);
	}

	/**
	 * Display the menu for a specific date.
	 *
	 * @param string $date date specified as YYYY-MM-DD. Defaults to todays date.
	 */
	public function actionView($date=null)
	{
		if (is_null($date))
			$date = date('Y-m-d');

		$this->sendResponse(array(
			'date' => $date
		));
		$this->sendResponse(404);
	}
}
