<?php

/**
 * This is the model class for table "OrderItem".
 *
 * The followings are the available columns in table 'OrderItem':
 * @property string $order
 * @property string $product
 * @property integer $amount
 */
class OrderItem extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return OrderItem the static model class
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
		return 'OrderItem';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('order, product, amount', 'required'),
			array('amount', 'numerical', 'integerOnly'=>true),
			array('order, product', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('order, product, amount', 'safe', 'on'=>'search'),
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
			'product0' => array(self::BELONGS_TO, 'Food', 'product'),
			'order0' => array(self::BELONGS_TO, 'Order', 'order'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'order' => 'Order',
			'product' => 'Product',
			'amount' => 'Amount',
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

		$criteria->compare('order',$this->order,true);

		$criteria->compare('product',$this->product,true);

		$criteria->compare('amount',$this->amount);

		return new CActiveDataProvider('OrderItem', array(
			'criteria'=>$criteria,
		));
	}
}