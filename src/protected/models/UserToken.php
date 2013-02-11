<?php

/**
 * This is the model class for table "UserToken".
 *
 * The followings are the available columns in table 'UserToken':
 * @property string $id
 * @property string $user_id
 * @property string $token
 * @property string $expires
 */
class UserToken extends CActiveRecord
{
	private $expiresOffset = 86400; // 24h
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
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id', 'required'),
			array('user_id', 'length', 'max'=>10),
			array('token', 'length', 'max'=>32),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, token, expires', 'safe', 'on'=>'search'),
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
			'expires' => 'Expires',
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
		$criteria->compare('expires',$this->expires,true);

		return new CActiveDataProvider('UserToken', array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Automatically set the token and its expires timestamp.
	 */
	public function beforeSave()
	{
		if ($this->isNewRecord)
		{
			$this->token = md5(openssl_random_pseudo_bytes(32));
			$this->expires = time() + $this->expiresOffset;
		}
		else
			throw new CDbException('You are not allowed to modify tokens');

		return parent::beforeSave();
	}

	/**
	 * @return boolean if the token is valid.
	 */
	public function isValid()
	{
		return strtotime($this->expires) > time();
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
