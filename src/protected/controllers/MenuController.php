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

		$menu = array();
		$meals = Food::model()->findAllByAttributes(array('date'=>$date));

		foreach ($meals as $meal)
		{
			$menuItem = new StdClass();
			$menuItem->id = $meal->id;
			$menuItem->parts = array();
			$menuItem->price = $meal->getPrice($user->role_id);

			foreach ($meal->foodParts as $foodPart)
				$menuItem->parts[] = $foodPart->name;
			
			$menu[] = $menuItem;
		}

		$this->sendResponse($menu);
	}
	
}
