<?php

/**
 * This is the model class for table "FoodPart".
 *
 * The followings are the available columns in table 'FoodPart':
 * @property string $id
 * @property string $food
 * @property string $name
 * @property string $diets
 */
class FoodPart extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return FoodPart the static model class
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
		return 'FoodPart';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, food, name', 'required'),
			array('id, food', 'length', 'max'=>10),
			array('name', 'length', 'max'=>50),
			array('diets', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, food, name, diets', 'safe', 'on'=>'search'),
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
			'food0' => array(self::BELONGS_TO, 'Food', 'food'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'food' => 'Food',
			'name' => 'Name',
			'diets' => 'Diets',
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

		$criteria->compare('food',$this->food,true);

		$criteria->compare('name',$this->name,true);

		$criteria->compare('diets',$this->diets,true);

		return new CActiveDataProvider('FoodPart', array(
			'criteria'=>$criteria,
		));
	}
}