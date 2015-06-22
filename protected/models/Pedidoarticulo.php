<?php

/**
 * This is the model class for table "pedidoarticulo".
 *
 * The followings are the available columns in table 'pedidoarticulo':
 * @property integer $idPedido
 * @property integer $idArticulo
 * @property integer $nCantidad
 * @property integer $bEntregado
 * @property double $nValorUnitario
 * @property double $nValorNeto
 *
 * @property Articulo $articulo
 */
class Pedidoarticulo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pedidoarticulo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idPedido, idArticulo, nCantidad, nValorUnitario, nValorNeto', 'required'),
			array('idPedido, idArticulo, nCantidad, bEntregado', 'numerical', 'integerOnly'=>true),
			array('nValorUnitario, nValorNeto', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('idPedido, idArticulo, nCantidad', 'safe', 'on'=>'search'),
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
            'articulo' => array(self::HAS_ONE, 'Articulo', 'idArticulo')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idPedido' => 'Cod. Pedido',
			'idArticulo' => 'Articulo',
			'nCantidad' => 'Cantidad',
			'bEntregado' => 'Entregado',
			'nValorUnitario' => 'Valor Unitario',
			'nValorNeto' => 'Valor Neto',
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

		$criteria->compare('idPedido',$this->idPedido);
		$criteria->compare('idArticulo',$this->idArticulo);
		$criteria->compare('nCantidad',$this->nCantidad);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Pedidoarticulo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
