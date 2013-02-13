<?php

/**
 * This is the model class for table "Food".
 *
 * The followings are the available columns in table 'Food':
 * @property string $id
 * @property string $date
 */
class Food extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Food the static model class
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
		return 'Food';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('date', 'safe'),
			// The following rule is used by search().
			array('id, date', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'foodParts' => array(self::HAS_MANY, 'FoodPart', 'food'),
			'userRoles' => array(self::MANY_MANY, 'UserRole', 'FoodPrice(food, userrole)'),
			'orderItems' => array(self::HAS_MANY, 'OrderItem', 'product'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'date' => 'Date',
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
		$criteria->compare('date',$this->date,true);

		return new CActiveDataProvider('Food', array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Returns the price for the specified role
	 * @param int $roleId the role 
	 * @return mixed the food price as a double or null if the price could not 
	 * be determined
	 */
	public function getPrice($roleId)
	{
		$foodPrice = FoodPrice::model()->findByAttributes(array(
			'food'=>$this->id, 'userrole'=>$roleId));

		if ($foodPrice !== null)
			return $foodPrice->price;
	}
	
}
