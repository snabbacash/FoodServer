<?php

class OrderController extends Controller
{
	public function filters()
	{
		return array();
	}

	/**
	 * Display all orders from today.
	 */
	public function actionList()
	{
		$this->checkAuth();
		$this->allow(array('GET', 'POST'));
		$this->sendResponse(array(
			(object) array('items' => array()),
			(object) array('items' => array())
		));
		// Unauthorized
		$this->sendResponse(401);
	}

	/**
	 * Display a specific order.
	 *
	 * @param string $date date specified as YYYY-MM-DD, defaulting to todays date.
	 */
	public function actionView($id)
	{
		$this->checkAuth();
		$this->allow(array('GET', 'PUT'));
		$this->sendResponse(array(
			'order id' => $id
		));
		$this->sendResponse(404);
	}

	/**
	 * Create a new order.
	 */
	public function actionCreate()
	{
		$this->checkAuth();
		$this->allow(array('GET', 'POST'));
		$this->sendResponse(201, array(
			'create order' => true
		));
		// Missing field
		$this->sendResponse(400);
	}

	/**
	 * Update an order
	 */
	public function actionUpdate($id)
	{
		$this->checkAuth();
		$this->allow(array('GET', 'PUT'));
		$this->sendResponse(array(
			'update order' => true
		));
		// Forbidden
		$this->sendResponse(403);
	}
}
