<?php

// Get some Amica parsers
include(dirname(__FILE__).'/../../../lib/amica/amica.php');


class GetAmicaCommand extends CConsoleCommand {

    public function run($args) {
    	$debug = true;

		$a = new AmicaParser();
		$menu = $a->getData('se');

		foreach ($menu as $date => $today) {
			//echo $date."\n";
			
			
			foreach ($today as $oneFood) {
				$food = new Food;
				$food->date=$date;
				
				if($debug){
					$food->id=0;
					$food->verificate();
				}
				else 
					$food->save();

				foreach ($oneFood['parts'] as $part ) {
					$fp = new FoodPart;
					$fp->food  = $food->id;
					$fp->name  = $part['name'];
					
					if(isset($part['info']))
						$fp->diets  = $part['info'];
					
					if ($debug) {
						$fp->id=0;
						$fp->verificate();
					} else {
						$fp->save();	
					}
					
				
				} // oneFood parts
				
				foreach ($oneFood['price'] as $group => $price ){				
					$userRole = UserRole::model()->	findByAttributes(array('name'=>$group));
					
					$fprice = new FoodPrice();
					$fprice->food = $food->id;
					$fprice->userrole = $userRole->id;
					$fprice->price = $price;
					if(debug){
						$fprice->id=0;
						$fprice->verificate();
					} else {
						$fprice->save();	
					}
					

				}
				//echo "\n";
			}
			//echo "\n";
		}
		
    } // run

}