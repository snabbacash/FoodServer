<?php

/**
 * This is the model class for table "Order".
 *
 * The followings are the available columns in table 'Order':
 * @property string $id
 * @property string $created
 * @property string $user
 * @property string $transaction
 * @property string $status
 */
class Order extends CActiveRecord
{
	
	/**
	 * Order statuses
	 */
	const STATUS_NEW = 'new';
	const STATUS_COMPLETED = 'completed';
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Order the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'Order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('user', 'required'),
			array('user, transaction', 'length', 'max'=>10),
			// The following rule is used by search().
			array('id, user, transaction', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'transaction0' => array(self::BELONGS_TO, 'Transaction', 'transaction'),
			'user0' => array(self::BELONGS_TO, 'User', 'user'),
			'orderItems' => array(self::HAS_MANY, 'OrderItem', 'order'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'created'=> 'Created',
			'user' => 'User',
			'transaction' => 'Transaction',
			'status'=>'Status',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id,true);
		$criteria->compare('user',$this->user,true);
		$criteria->compare('transaction',$this->transaction,true);

		return new CActiveDataProvider('Order', array(
			'criteria'=>$criteria,
		));
	}

	/**
	* Calculates the total price of current Order
	* @return total price for order
	*/
	public function price(){
		$user =  User::model()->findByAttributes(array('id'=>$this->user));
		$totalsum = 0.0;
		
		foreach ($this->orderItems as $item) {
			$price = FoodPrice::model()->findByAttributes(array(
				'food' => $item->product, 
				'userrole'=>$user->role_id 
			));
			$totalsum += ($price->price * $item->amount);
		}

		return $totalsum;
	}
    
    public function pay(){
		$user =  User::model()->findByAttributes(array('id'=>$this->user));
        $price = $this->price();
        if(!isset($this->transaction) && $user->balance >= $price){
            $transaction = new Transaction();
            $transaction->setAttribute("amount", -$price);
            $transaction->setAttribute("timestamp", time());
            if (!$transaction->save())
                throw new CHttpException(500, "Unable to save transaction".print_r($transaction->getErrors(),true));
            $user->setAttribute("balance", $user->balance-$price);
            if (!$user->save()){
                $transaction->delete();
                throw new CHttpException(500, "Unable to save user");
            }
            $this->setAttribute("status", "paid");
            $this->setAttribute("transaction", $transaction->getPrimaryKey());
            $this->save();
        }
    }
    public function refund(){
       
        if(isset($this->transaction)){
            $user =  User::model()->findByAttributes(array('id'=>$this->user));
            $transaction = Transaction::model()->findByPk($this->transaction);
            //det blir minus för transaction som "Kostar" är -. -+- = +
            $user->setAttribute("balance", $user->balance-$transaction->amount);
            if (!$user->save()){
                throw new CHttpException(500, "Unable to save user");
            }
            $this->setAttribute("transaction", null);
            $this->setAttribute("status","refunded");
            $this->save();
            $transaction->delete();
        }
    }

}
