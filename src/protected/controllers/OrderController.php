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
				'RestrictHttpMethodsFilter + list, create',
				'methods'=>array('GET', 'POST'),
			),
			array(
				'RestrictHttpMethodsFilter + view, update',
				'methods'=>array('GET', 'PUT'),
			),
		), parent::filters());
	}
	
	/**
	 * Display all orders from today.
	 */
	public function actionList()
	{
		// @TODO how is this supposed to do? It returns: Invalid argument supplied for foreach()
		// $order = User::model()->with('orders.orderItems')->findAll();
		$this->sendResponse(array(
			$this->token->user->orders, // @TODO fetch orderItems joined with food etc...
		));
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
