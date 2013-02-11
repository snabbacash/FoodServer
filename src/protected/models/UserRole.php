<?php

/**
 * This is the model class for table "UserRole".
 *
 * The followings are the available columns in table 'UserRole':
 * @property string $id
 * @property string $name
 */
class UserRole extends CActiveRecord
{
	
	/**
	 * Role names
	 */
	const ROLE_STUDENT = 'student';
	const ROLE_STAFF   = 'staff';
	const ROLE_OTHER   = 'other';
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return UserRole the static model class
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
		return 'UserRole';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name', 'required'),
			array('name', 'length', 'max'=>45),
			// The following rule is used by search().
			array('id, name', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'foods' => array(self::MANY_MANY, 'Food', 'FoodPrice(food, userrole)'),
			'users' => array(self::HAS_MANY, 'User', 'role'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'name' => 'Name',
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
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider('UserRole', array(
			'criteria'=>$criteria,
		));
	}
}
