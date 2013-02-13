<?php

/**
 * A Menu model for outputing sets of food items bundled together.
 */
class Menu extends CModel
{
	/**
	 * @param string the specified as Y-m-d
	 * @return object menu structure.
	 */
	public static function findByDate($date)
	{
		$meals = Food::model()->findAllByAttributes(array('date'=>$date));

		// Set the wrapper as there's no model for this.
		$menu = new stdClass();
		$menu->date = $date;
		$menu->menu = $meals;

		return $menu;
	}

	/**
	 * @param int the week number according to ISO-8601.
	 */
	public function findByWeek($week)
	{
		// @TODO errr how to group these by date?
		$menus = Food::model()->findAll('WEEK(date,1)=:week', array(':week'=>$week));
		return $menus;

	}

	/**
	 * @todo im not really grasping the potential of this. // oxy
	 */
	public function attributeNames()
	{
		return array('date', 'menu');
	}
}
