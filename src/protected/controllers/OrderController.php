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
	public function actionListDate($date)
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
			$jsonData[] = $this->getOrderArray($order);
		}

		$this->sendResponse($jsonData);
	}
    public function actionList()
    {
        $user = User::model()->findByToken($this->token);
        
        if($user->role->name == "amica"){
          $this->actionListStatus("pending");
          break;
        } else{
            $orders = Order::model()->findAllByAttributes(array("user"=>$user->id));
        
            $jsonData = array();
            foreach ($orders as $order)
            {
                $jsonData[] = $this->getOrderArray($order);
            }

            $this->sendResponse($jsonData);
        }
    }
	/**
	 * Returns all orders for the specified status
	 * @param string $status
	 */
	public function actionListStatus($status)
	{

        if ($status == "pending"){
            $orders = Order::model()->findAllByAttributes(array("status"=>array("new","refunded")));
        } else {
            $orders = Order::model()->findAllByAttributes(array("status"=>$status));
        }
		// Find the orders and return them
		//$orders = Order::model()->findAll('status = :status', array(':status'=>$status));
		$jsonData = array();

		foreach ($orders as $order)
		{
			$jsonData[] = $this->getOrderArray($order);
		}

		$this->sendResponse($jsonData);
	}

	/**
	 * Display a specific order.
	 */
	public function actionView($id)
	{
        $this->sendResponse((object) $this->getOrderArray($this->getOrder($id)));
	}

    /**
     *   Lets a User delete own orders
    */
    public function actionDelete($id){
        $user = User::model()->findByToken($this->token);
        $order = Order::model()->findByPk($id);
       

        if($order->transaction!==null)
            throw new CHttpException(403, 'You can\'t delete a Paid order');

        if($order->user != $user->id)
            throw new CHttpException(403, 'You can only delete your own orders');


        if( OrderItem::model()->deleteAllByAttributes(array('order'=>$order->id))
            && $order->delete())
        {
            $this->sendResponse('Order deleted');
        } else {
            throw new CHttpException(500, 'Could not delete order');
        }


    }


	/**
	 * Create a new order.
	 */
	public function actionCreate()
	{
		// TODO: Delete failed attempt of creating order

		$user = User::model()->findByToken($this->token);
        $order = new Order();
        $order->setAttribute("user",$user->getAttribute("id"));
        $this->validate('orders.create', $this->decodedJsonData);
        if (!$order->save())
            throw new CHttpException(500, 'Unable to create order');
        $orderItems = array();
        foreach($this->decodedJsonData->items as $item){
            $orderItem = new OrderItem();
            $orderItem->setAttributes(array(
                "product"=>$item->id,
                "amount"=>$item->amount,
                "order"=>$order->getAttribute("id")
            ));
            if (!$orderItem->save()) {
            	OrderItem::model()->deleteAllByAttributes(array('order'=>$order->id));
            	$order->delete();

            	throw new CHttpException(500, 'Unable to create orderItem');
            }
            $orderItems[] = $this->getOrderItemArray($orderItem);
        }
        $this->processOrder($order);
	}

	/**
	 * Update an order
	 * @param integer $id
	 */
	public function actionUpdate($id)
	{
        $order = $this->getOrder($id);
		$this->validate('orders.update', $this->decodedJsonData);
        
        if(isset($this->decodedJsonData->status))
            $order->setAttribute("status", $this->decodedJsonData->status);
        
        if(!is_null($order->transaction) && isset($this->decodedJsonData->items)){
            throw new CHttpException(400, "Cant't change items, Order $id already paid");

        }else{
            if(isset($this->decodedJsonData->items)){
                  foreach($order->orderItems as $orderItem){
                    $exists = false;
                    foreach($this->decodedJsonData->items as $key => $newOrderItem){
                        if($orderItem->product == $newOrderItem->id){
                            $orderItem->setAttribute("amount", $newOrderItem->amount);
                            unset($this->decodedJsonData->items[$key]);
                            $exists = true;
                            break;
                        }
                    }
                    if(!$exists){
                        $orderItem->delete();
                    }
                }
                foreach($this->decodedJsonData->items as $item){
                    $orderItem = new OrderItem();
                    $orderItem->setAttribute("order", $id);
                    $orderItem->setAttribute("product", $item->id);
                    $orderItem->setAttribute("amount", $item->amount);
                    if (!$orderItem->save())
                        throw new CHttpException(500, 'Unable to save orderItem');
                }
            }
        }
        $this->processOrder($order);
	}
    public function processOrder(Order $order){
		$user = User::model()->findByToken($this->token);
        if(isset($this->decodedJsonData->confirmed)){
            if($user->role->name == "amica"){
                if($this->decodedJsonData->confirmed){
                    $order->pay();
                }else{
                    $order->refund();
                }
                $this->sendResponse($this->getOrderArray($order));
            }else{
                throw new CHttpException(403, 'You need to be amica');
            }
        }else {
            //@TODO fixa ACL så att users int kan editera andras orders.
            if ($user->balance >= $user->reservedPayment() ){
                $this->sendResponse($this->getOrderArray($order));
            } else {
                OrderItem::model()->deleteAllByAttributes(array('order'=>$order->id));
                $order->delete();
                // 402 Payment Required, Or something else
                throw new CHttpException(402, 'Cheap Bastard, you need more funds'.$user->balance ." reserved: " .$user->reservedPayment());
            }
        }
    }
	/**
	 * Get order by ID
	 * @param integer $id
	 */
    public function getOrder($id){
		$order = Order::model()->findByPk($id);
		if($order){
            return $order;
        }else{
            throw new CHttpException(404, "Order $id not found");
        }
    }
	/**
	 * Get array from order
	 * @param Order $order
	 */
    public function getOrderArray(Order $order){
        $orderItems = array();
        foreach($order->orderItems as $orderItem){
            $orderItems[] = $this->getOrderItemArray($orderItem);
        }
        return array(
            'id'=>$order->id,
            'created'=>$order->created,
            'user'=>$order->user,
            'transaction'=>$order->transaction,
            'status'=>$order->status,
            'items' => $orderItems
        );
    }
	/**
	 * Get orderItem by ID
	 * @param integer $id
	 */
    public function getOrderItem($id){
		$order = OrderItem::model()->findByPk($id);
		if($order){
            return $order;
        }else{
            throw new CHttpException(404, "OrderItem $id not found");
        }
    }
	/**
	 * Get array from orderItem
	 * @param OrderItem $orderItem
	 */
    public function getOrderItemArray(OrderItem $orderItem){
        return array(
                //'id' => $orderItem->getPrimaryKey(),
                'product' => $orderItem->getAttribute("product"),
                'amount' => $orderItem->getAttribute("amount"),
            );
    }
}
