<?php

/**
 * This is the model class for table "Order".
 *
 * The followings are the available columns in table 'Order':
 * @property string $id
 * @property string $user
 * @property string $transaction
 */
class Order extends CActiveRecord
{
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
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user', 'required'),
			array('user, transaction', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user, transaction', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
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
			'user' => 'User',
			'transaction' => 'Transaction',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);

		$criteria->compare('user',$this->user,true);

		$criteria->compare('transaction',$this->transaction,true);

		return new CActiveDataProvider('Order', array(
			'criteria'=>$criteria,
		));
	}
}
