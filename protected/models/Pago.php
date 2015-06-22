<?php

/**
 * This is the model class for table "pago".
 *
 * The followings are the available columns in table 'pago':
 * @property integer $idPago
 * @property integer $idFormaPago
 * @property double $nTotal
 * @property string $fFechaCreacion
 * @property string $fFechaCancelacion
 * @property integer $bFinalizado
 *
 * The followings are the available model relations:
 * @property Formapago $formaPago
 * @property Pedido[] $pedidos
 */
class Pago extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pago';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idFormaPago, nTotal, fFechaCreacion', 'required'),
			array('idFormaPago, bFinalizado', 'numerical', 'integerOnly'=>true),
			array('nTotal', 'numerical'),
			array('fFechaCancelacion', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('idPago, idFormaPago, nTotal, fFechaCreacion, fFechaCancelacion, bFinalizado', 'safe', 'on'=>'search'),
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
			'formaPago' => array(self::BELONGS_TO, 'Formapago', 'idFormaPago'),
			'pedidos' => array(self::HAS_MANY, 'Pedido', 'idPago'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idPago' => 'Cod. Pago',
			'idFormaPago' => 'Forma de Pago',
			'nTotal' => 'Total',
			'fFechaCreacion' => 'Fecha Creacion',
			'fFechaCancelacion' => 'Fecha Cancelacion',
			'bFinalizado' => 'Finalizado',
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

		$criteria->compare('idPago',$this->idPago);
		$criteria->compare('idFormaPago',$this->idFormaPago);
		$criteria->compare('nTotal',$this->nTotal);
		$criteria->compare('fFechaCreacion',$this->fFechaCreacion,true);
		$criteria->compare('fFechaCancelacion',$this->fFechaCancelacion,true);
		$criteria->compare('bFinalizado',$this->bFinalizado);

		$criteria->order = 'idPago DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Pago the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
