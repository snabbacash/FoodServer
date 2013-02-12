<?php

class OrderController extends Controller
{
	
	/**
	 * @return array the filters for this controller
	 */
	public function filters()
	{
		return array_merge(array(
			array(
				'RestrictHttpMethodsFilter + list',
				'methods'=>array('GET'),
			),
			array(
				'RestrictHttpMethodsFilter + view, update',
				'methods'=>array('GET', 'PUT'),
			),
		), parent::filters());
	}
	
	/**
	 * Returns all orders for the specified date
	 * @param string $date date in YYYY-MM-DD format
	 */
	public function actionList($date)
	{
		// Make sure $date is in the sought format
		$time = CDateTimeParser::parse($date, 'yyyy-MM-dd');

		if ($time === false)
			throw new CHttpException(400, 'Invalid date specified');

		// Find the orders and return them
		$orders = Order::model()->findAll('DATE(created) = :date', array(':date'=>$date));
		$jsonData = array();

		foreach ($orders as $order)
		{
			// TODO: Make a toJson() method somewhere (perhaps a base model)
			$jsonData[] = array(
				'id'=>$order->id,
				'created'=>$order->created,
				'user'=>$order->user,
				'transaction'=>$order->transaction,
				'status'=>$order->status,
			);
		}

		$this->sendResponse($jsonData);
	}

	/**
	 * Display a specific order.
	 *
	 * @param string $date date specified as YYYY-MM-DD, defaulting to todays date.
	 */
	public function actionView($id)
	{
		$this->sendResponse((object) array(
			'id' => $id
		));
		$this->sendResponse(404);
	}

	/**
	 * Create a new order.
	 */
	public function actionCreate()
	{
		if (isset($_POST['items'])) {
			$id = rand();
			$this->setHeader('Location', "/orders/$id");
			$this->sendResponse(201, array(
				'id' => $id
			));
		}
		// Missing field
		$this->sendResponse(400);
	}

	/**
	 * Update an order
	 */
	public function actionUpdate($id)
	{
		$data = $this->getPutData();
		// Forbidden
		if (isset($data['id']))
			$this->sendResponse(403);

		$this->sendResponse((object) array(
			'id' => $id
		));
	}
}
