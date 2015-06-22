<?php

/**
 * This is the model class for table "pedidodocumento".
 *
 * The followings are the available columns in table 'pedidodocumento':
 * @property integer $idPedido
 * @property integer $idDocumento
 * @property integer $idEstado
 * @property integer $nCopias
 * @property integer $nImpresos
 * @property integer $nAnillado
 * @property integer $bEntregado
 * @property double $nValorUnitario
 * @property double $nValorNeto
 * @property double $nValorAnillados
 *
 * @property Documento $documento
 * @property Pedido $pedido
 * @property Pedidodocumentoestado $estado
 */
class Pedidodocumento extends CActiveRecord
{
	public $idSucursal;
	public $cantidad;
	public $anillado;
	public $pedidosInvolucrados;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pedidodocumento';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idPedido, idDocumento, nValorUnitario, nValorNeto, nValorAnillados', 'required'),
			array('idPedido, idDocumento, idEstado, nCopias, nImpresos, nAnillado, bEntregado', 'numerical', 'integerOnly'=>true),
			array('nValorUnitario, nValorNeto, nValorAnillados', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('idPedido, idDocumento, idEstado, nCopias, idPromocion', 'safe', 'on'=>'search'),
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
            'documento' => array(self::BELONGS_TO, 'Documento', 'idDocumento'),
            'pedido' => array(self::BELONGS_TO, 'Pedido', 'idPedido'),
            'estado' => array(self::BELONGS_TO, 'Pedidodocumentoestado', 'idEstado'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idPedido' => 'Pedido',
			'idDocumento' => 'Documento',
			'idEstado' => 'Estado',
			'nCopias' => 'Cant Copias',
			'nImpresos' => 'Impresos',
			'nAnillado' => 'Cant. Anillados',
			'bEntregado' => 'Entregado',
			'nValorUnitario' => 'Valor Unitario',
			'nValorNeto' => 'Valor Neto',
			'nValorAnillados' => 'Valor Anillados',
		);
	}

	public function scopes() {
        return array(
            'pendientesImpresion'=>array(
                'condition'	=>	't.nImpresos < t.nCopias AND t.bEntregado = 0 AND t.idEstado = 1 AND pedido.idEstado = 2',
                'group'   	=>	't.idDocumento, pedido.idSucursal',
                'select'  => array('SUM(t.nCopias) as cantidad','SUM(t.nAnillado) as anillado', 'GROUP_CONCAT(DISTINCT pedido.idPedido SEPARATOR ",") AS pedidosInvolucrados'),
            ),
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
		$criteria->compare('idDocumento',$this->idDocumento);
		$criteria->compare('idEstado',$this->idEstado);
		$criteria->compare('nCopias',$this->nCopias);
		$criteria->compare('idPromocion',$this->idPromocion);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function searchImpresion()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('idPedido',$this->idPedido);
		$criteria->compare('idDocumento',$this->idDocumento);
		$criteria->compare('idEstado',$this->idEstado);
		$criteria->compare('nCopias',$this->nCopias);
		$criteria->compare('idPromocion',$this->idPromocion);

		$criteria->group = "t.idDocumento";
        //$criteria->select = "t.idPedido, t.idDocumento, t.idEstado, SUM(t.nCopias) as nCopias, t.idPromocion, SUM(t.nImpresos) as nImpresos";
        $criteria->select = "SUM(t.nCopias) as nCopias, SUM(t.nImpresos) as nImpresos, MAX(documento.nPaginas) as nPaginas";

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Pedidodocumento the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/*
	 * Cambia el estado de Entregado o no. Si el documento es entregado, se entiende que ya fue impreso.
	*/
	public function ChangeEntregado($value) {
		if($this->bEntregado == 0) {
			$this->bEntregado = $value;
			if($value == 1) {
				$this->idEstado = 2;
				return true;
			}
		}
		return false;
	}

	public function ChangeEstado($value) {
		if($this->idEstado == 1) { 
			if($value == 2) {
				$this->idEstado = $value;
				return true;
			}
		}
		return false;
	}
}
