<?php

class Menu extends CModel
{
	public static function findByDate($date)
	{
		$meals = Food::model()->findAllByAttributes(array('date'=>$date));

		// Set the wrapper as there's no model for this.
		$menu = new stdClass();
		$menu->date = $date;
		$menu->menu = $meals;

		return $menu;
	}

	public function findByWeek($week)
	{
		// @TODO errr how to group these by date?
		$menus = Food::model()->findAll('WEEK(date,1)=:week', array(':week'=>$week));
		return $menus;

	}

	public function attributeNames()
	{
		return array('date', 'menu');
	}
}
