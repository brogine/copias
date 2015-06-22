<?php

/**
 * This is the model class for table "configuracion".
 *
 * The followings are the available columns in table 'configuracion':
 * @property integer $idConfiguracion
 * @property string $sKey
 * @property string $sUserKey
 * @property string $sValue
 */
class Configuracion extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'configuracion';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sKey, sUserKey, sValue', 'required'),
			array('sKey', 'length', 'max'=>100),
			array('sUserKey, sValue', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('idConfiguracion, sKey, sUserKey, sValue', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idConfiguracion' => 'Cód. Configuración',
			'sKey' => 'Campo Sistema',
			'sUserKey' => 'Campo Usuario',
			'sValue' => 'Valor',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('idConfiguracion',$this->idConfiguracion);
		$criteria->compare('sKey',$this->sKey,true);
		$criteria->compare('sUserKey',$this->sUserKey,true);
		$criteria->compare('sValue',$this->sValue,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Configuracion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function PrecioAnillado() {
		$cache = Yii::app()->cache;
		if($cache->get('vAnillado')) {
			return $cache->get('vAnillado');
		} else {
			$valor = Configuracion::model()->findByAttributes(array('sKey'=>'vAnillado'))->sValue;
			$cache->set('vAnillado', $valor, 3600);
			return $valor;
		}
	}
}
