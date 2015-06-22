<?php

/**
 * This is the model class for table "impresiondetalle".
 *
 * The followings are the available columns in table 'impresiondetalle':
 * @property integer $idImpresionDetalle
 * @property integer $idImpresion
 * @property integer $idPedido
 * @property integer $idDocumento
 * @property integer $nCantidad
 * @property integer $idSucursalEntrega
 * @property integer $idSucursalImpresion
 * @property integer $nAnillados
 *
 * The followings are the available model relations:
 * @property Sucursal $sucursalEntrega
 * @property Sucursal $sucursalImpresion
 * @property Documento $documento
 * @property Impresion $idImpresion0
 * @property Pedido $idPedido0
 */
class Impresiondetalle extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'impresiondetalle';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idImpresion, idPedido, idDocumento, nCantidad, idSucursalEntrega', 'required'),
			array('idImpresion, idPedido, idDocumento, nCantidad, idSucursalEntrega, idSucursalImpresion, nAnillados', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('idImpresionDetalle, idImpresion, idPedido, idDocumento, nCantidad, idSucursal', 'safe', 'on'=>'search'),
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
			'sucursalEntrega' => array(self::BELONGS_TO, 'Sucursal', 'idSucursalEntrega'),
			'sucursalImpresion' => array(self::BELONGS_TO, 'Sucursal', 'idSucursalImpresion'),
			'documento' => array(self::BELONGS_TO, 'Documento', 'idDocumento'),
			'idImpresion0' => array(self::BELONGS_TO, 'Impresion', 'idImpresion'),
			'idPedido0' => array(self::BELONGS_TO, 'Pedido', 'idPedido'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idImpresionDetalle' => 'Id Impresion Detalle',
			'idImpresion' => 'Impresion',
			'idPedido' => 'Pedido',
			'idDocumento' => 'Documento',
			'nCantidad' => 'Cantidad',
			'idSucursalEntrega' => 'Sucursal de Entrega',
			'idSucursalImpresion' => 'Sucursal de Impresión',
			'nAnillados' => 'Cant. anillados',
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

		$criteria->compare('idImpresionDetalle',$this->idImpresionDetalle);
		$criteria->compare('idImpresion',$this->idImpresion);
		$criteria->compare('idPedido',$this->idPedido);
		$criteria->compare('idDocumento',$this->idDocumento);
		$criteria->compare('nCantidad',$this->nCantidad);
		$criteria->compare('idSucursalEntrega',$this->idSucursal);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Impresiondetalle the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
