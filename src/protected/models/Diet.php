<?php

/**
 * This is the model class for table "Diet".
 *
 * The followings are the available columns in table 'Diet':
 * @property string $id
 * @property string $name
 * @property string $short
 */
class Diet extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Diet the static model class
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
		return 'Diet';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name, short', 'required'),
			array('name', 'length', 'max'=>50),
			array('short', 'length', 'max'=>5),
			// The following rule is used by search().
			array('id, name, short', 'safe', 'on'=>'search'),
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
			'id' => 'Id',
			'name' => 'Name',
			'short' => 'Short',
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
		$criteria->compare('short',$this->short,true);

		return new CActiveDataProvider('Diet', array(
			'criteria'=>$criteria,
		));
	}
}
