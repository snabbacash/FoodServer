<?php

/**
 * This is the model class for table "UserToken".
 *
 * The followings are the available columns in table 'UserToken':
 * @property string $user_id
 * @property string $token
 * @property string $created
 */
class UserToken extends CActiveRecord
{
	
	/**
	 * @var int how long (seconds) a token should remain valid
	 */
	private $_tokenLifetime = 2592000; // a month
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return UserToken the static model class
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
		return 'UserToken';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('user_id, token', 'required'),
			array('token', 'unique'),
			array('user_id', 'length', 'max'=>10),
			array('token', 'length', 'max'=>32),
			array('id, user_id, token, created', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'user_id' => 'User',
			'token' => 'Token',
			'created' => 'Created',
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
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('token',$this->token,true);
		$criteria->compare('created',$this->created,true);

		return new CActiveDataProvider('UserToken', array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Generates and returns a new token, guaranteed to be unique
	 * @return string the token
	 */
	public function generateToken()
	{
		do
		{
			$token = md5(openssl_random_pseudo_bytes(32));
		}
		while (self::model()->findByAttributes(array('token' => $token)) !== null);

		return $token;
	}

	/**
	 * @return boolean if the token is valid.
	 */
	public function isValid()
	{
		return strtotime($this->created) + $this->_tokenLifetime > time();
	}

	static public function validateToken($tokenString)
	{
		$token = UserToken::model()->findByAttributes(array('token'=>$tokenString));
		if (count($token) == 0 || !$token->isValid())
			return false;
		else
			return $token;
	}
}
