<?php

/**
 * This is the model class for table "User".
 *
 * The followings are the available columns in table 'User':
 * @property string $id
 * @property string $username
 * @property double $balance
 * @property string $role
 * 
 * @property UserToken $token
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return User the static model class
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
		return 'User';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('username, role', 'required'),
			array('balance', 'numerical'),
			array('id, role', 'length', 'max'=>10),
			array('username', 'length', 'max'=>45),
			array('id, username, balance, role', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'orders' => array(self::HAS_MANY, 'Order', 'user'),
			'role0' => array(self::BELONGS_TO, 'UserRole', 'role'),
			'token' => array(self::HAS_ONE, 'UserToken', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'username' => 'Username',
			'balance' => 'Balance',
			'role' => 'Role',
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('balance',$this->balance);
		$criteria->compare('role',$this->role,true);

		return new CActiveDataProvider('User', array(
			'criteria'=>$criteria,
		));
	}
}