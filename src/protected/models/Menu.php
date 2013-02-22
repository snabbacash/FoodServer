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
		$meals = Food::model()->findAll(array(
				'condition'=>'date=:date OR date IS NULL',
				'params'=>array(':date'=>$date),
				'order'=>'date',
		));
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
		$menus = Food::model()->findAll(array(
				'condition'=>'WEEK(date,1)=:week OR date IS NULL',
				'params'=>array(':week'=>$week),
				'order'=>'date',
		));
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
