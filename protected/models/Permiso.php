<?php

/**
 * This is the model class for table "permiso".
 *
 * The followings are the available columns in table 'permiso':
 * @property integer $idRol
 * @property integer $idModulo
 */
class Permiso extends CActiveRecord
{
    const MODULO_ADMIN = 1;
    const MODULO_ARTICULO = 2;
    const MODULO_CARRERA = 3;
    const MODULO_CURSO = 4;
    const MODULO_DOCUMENTO = 5;
    const MODULO_INSTITUCION = 6;
    const MODULO_MATERIA = 7;
    const MODULO_PEDIDO = 8;
    const MODULO_PROFESOR = 9;
    const MODULO_PROMOCION = 10;
    const MODULO_ROL = 11;
    const MODULO_SUCURSAL = 12;
    const MODULO_USUARIO = 13;
    const MODULO_IMPRESION = 14;
    const MODULO_REPORTES = 15;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'permiso';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idRol, idModulo', 'required'),
			array('idRol, idModulo', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('idRol, idModulo', 'safe', 'on'=>'search'),
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
			'idRol' => 'Rol',
			'idModulo' => 'Modulo',
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

		$criteria->compare('idRol',$this->idRol);
		$criteria->compare('idModulo',$this->idModulo);

		$criteria->order = 'idRol DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Permiso the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
