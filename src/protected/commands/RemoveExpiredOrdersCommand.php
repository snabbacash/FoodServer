RemoveExpiredOrdersCommand<?php

// Get some Amica parsers
include(dirname(__FILE__).'/../../../lib/amica/amica.php');


class RemoveExpiredOrdersCommand extends CConsoleCommand {

	public function run($args) {
		$yesterday = strtotime('yesterday');
		echo "\nYesterday: $yesterday\n";

		$orders = Order::model()->findAll(
			'UNIX_TIMESTAMP(created) < :date AND transaction IS NULL',
			array(':date'=>$yesterday)
		);
		$oldOrders = count($orders);
		echo "Found $oldOrders old orders\n";
		$n = 1;
		foreach ($orders as $order){
			echo "Deleting $n of $oldOrders\n";
			echo "\t" . $order->id . " Created: " . $order->created ."\n";
			OrderItem::model()->deleteAllByAttributes(array('order'=>$order->id));
			$order->delete();

			$n++;
		}

	}
}	