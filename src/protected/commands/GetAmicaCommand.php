<?php

// Get some Amica parsers
include(dirname(__FILE__).'/../../../lib/amica/amica.php');


class GetAmicaCommand extends CConsoleCommand {

    public function run($args) {

		$a = new AmicaParser();
		$menu = $a->getData('se');

		foreach ($menu as $date => $today) {
			echo $date."\n";
			foreach ($today as $oneFood) {
				echo "\t";
				echo $oneFood['price'][0].": ";
				foreach ($oneFood['parts'] as $part ) {
					echo $part['name'].", ";
					
				}
				echo "\n";
			}
			echo "\n";
		}
		
    } // run
	
	 
	
}