<?php

/**
 * This is the model class for table "User".
 *
 * The followings are the available columns in table 'User':
 * @property string $id
 * @property string $username
 * @property string $name
 * @property double $balance
 * @property string $role_id
 * 
 * @property UserRole $role
 * @property UserToken $token
 */
class User extends CActiveRecord implements ApiSerializable
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
			array('username, name, role_id', 'required'),
			array('balance', 'numerical'),
			array('id, role_id', 'length', 'max'=>10),
			array('username', 'length', 'max'=>45),
			array('name', 'length', 'max'=>255),
			array('id, username, name, balance, role_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'orders' => array(self::HAS_MANY, 'Order', 'user'),
			'role' => array(self::BELONGS_TO, 'UserRole', 'role_id'),
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
			'role_id' => 'Role',
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
		$criteria->compare('role_id',$this->role,true);

		return new CActiveDataProvider('User', array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Finds a user based on his associated token
	 * @param string $token the token
	 * @return User the user model or null if not found
	 */
	public function findByToken($token)
	{
		$with = array('token'=>array(
				'condition'=>'token = :token',
				'params'=>array(':token'=>$token)));

		return self::model()->with($with)->find();
	}

	/**
	 * @return array a serialized version of the User to be usd by the API.
	 */
	public function __toJSON()
	{
		return array(
			'username'=>$this->username,
			'name'=>$this->name,
			'balance'=>(double) $this->balance,
			'role'=>$this->role->name
		);
	}
	
}
