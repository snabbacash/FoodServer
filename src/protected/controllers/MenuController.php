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
		$menus = Menu::findByWeek($week);
		$this->sendResponse($menus);
	}

	/**
	 * Display the menu for a specific date. If the date is omitted, today's 
	 * menu will be returned
	 * @param string $date date specified as YYYY-MM-DD
	 */
	public function actionView($date = null)
	{
		if (is_null($date))
			$date = date('Y-m-d');

		// Find the user, we need his role
		// TODO: Add Controller::loadUser()
		$user = User::model()->findByToken($this->token);

		$menu = Menu::findByDate($date);

		$this->sendResponse($menu);
	}
}
