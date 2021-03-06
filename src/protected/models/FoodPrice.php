<?php

/**
 * This is the model class for table "FoodPrice".
 *
 * The followings are the available columns in table 'FoodPrice':
 * @property string $food
 * @property string $userrole
 * @property string $price
 */
class FoodPrice extends CActiveRecord implements ApiSerializable
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return FoodPrice the static model class
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
		return 'FoodPrice';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('food, userrole, price', 'required'),
			array('food, userrole', 'length', 'max'=>10),
			array('price', 'length', 'max'=>5),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('food, userrole, price', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'food' => 'Food',
			'userrole' => 'Userrole',
			'price' => 'Price',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('food',$this->food,true);
		$criteria->compare('userrole',$this->userrole,true);
		$criteria->compare('price',$this->price,true);

		return new CActiveDataProvider('FoodPrice', array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return array a serialized version of the FoodPrice to be usd by the API.
	 */
	public function serialize()
	{
		return array(
			'role'=>$this->userrole,
			'price'=>$this->price
		);
	}
}
