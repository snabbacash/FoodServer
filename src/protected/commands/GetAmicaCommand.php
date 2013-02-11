<?php

// Get some Amica parsers
include(dirname(__FILE__).'/../../../lib/amica/amica.php');


class GetAmicaCommand extends CConsoleCommand {

	public function run($args) {
		$debug = false;

		$a = new AmicaParser();
		$menu = $a->getData('se');
		$days = count($menu);
		echo "Found $days days worth of Food\n";
		$cDay = 1;
		foreach ($menu as $date => $today){
			echo "Adding day $cDay/$days\n";
			$cDay++;
			
			$foodsToday = count($today);
			$cFood=1;
			foreach ($today as $oneFood) {
				echo "\tAdding food $cFood/$foodsToday";
				$cFood++;

				$food = new Food;
				$food->date=$date;
				$food->save();

				foreach ($oneFood['parts'] as $part ) {
					echo ".";

					$fp = new FoodPart;
					$fp->food  = $food->id;
					$fp->name  = $part['name'];
					
					if ( isset($part['info']) ) 
						$fp->diets  = $part['info'];
					
					$fp->save();
					
				} // oneFood parts
				
				foreach ($oneFood['price'] as $group => $price ){				
					echo ".";
					
					$userRole = UserRole::model()->	findByAttributes(array('name'=>$group));
					
					$fprice = new FoodPrice();
					$fprice->food = $food->id;
					$fprice->userrole = $userRole->id;
					$fprice->price = $price;
					
					if($debug)
						$fprice->id=0;
					else 
						$fprice->save();	
					
				} // Price
				echo "\n";
					
			} // OneFood 
			
		} // Day
		
	} // Run 

} // GetAmicaCommand
