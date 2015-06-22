<?php

/**
 * This is the model class for table "articulo".
 *
 * The followings are the available columns in table 'articulo':
 * @property integer $idArticulo
 * @property integer $idSucursal
 * @property string $sDescripcion
 * @property string $nPrecio
 * @property integer $nStock
 * @property string $sObservaciones
 *
 * The followings are the available model relations:
 * @property Sucursal $sucursal
 * @property Pedido[] $pedidos
 */
class Articulo extends CActiveRecord implements IECartPosition
{
    public $keyword;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'articulo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sDescripcion, nPrecio', 'required'),
			array('idSucursal, nStock', 'numerical', 'integerOnly'=>true),
			array('sDescripcion', 'length', 'max'=>150),
			array('nPrecio', 'numerical'),
			array('sObservaciones', 'length', 'max'=>300),
            array('keyword','safe', 'on'=>'search'),
			array('idArticulo, idSucursal, sDescripcion, nPrecio, nStock', 'safe', 'on'=>'search'),
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
			'sucursal' => array(self::BELONGS_TO, 'Sucursal', 'idSucursal'),
			'pedidos' => array(self::MANY_MANY, 'Pedido', 'pedidoarticulo(idArticulo, idPedido)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idArticulo' => 'Código',
			'idSucursal' => 'Sucursal',
			'sDescripcion' => 'Descripcion',
			'nPrecio' => 'Precio',
			'nStock' => 'Stock',
			'sObservaciones' => 'Observaciones',
		);
	}

	public function getId(){
        return 'a'.$this->idArticulo;
    }

    public function getPrice(){
        return $this->nPrecio;
    }

    public function getExtra() {
    	return 0;
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

		$criteria->compare('idArticulo',$this->idArticulo);
		$criteria->compare('idSucursal',$this->idSucursal);
		$criteria->compare('sDescripcion',$this->sDescripcion,true);
		$criteria->compare('nPrecio',$this->nPrecio,true);
		$criteria->compare('nStock',$this->nStock);

		$criteria->order = 'idArticulo DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function searchTextLive()
    {
        $criteria = new CDbCriteria;
        $keywordIdArticulo = str_replace("a", "", $this->keyword);

        $criteria -> compare('idArticulo',$keywordIdArticulo, false, 'OR');
        $criteria -> compare('sDescripcion',$this->keyword,true,'OR');
        $criteria -> compare('nPrecio',$this->keyword,true,'OR');

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Articulo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
