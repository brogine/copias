<?php

/**
 * This is the model class for table "pedido".
 *
 * The followings are the available columns in table 'pedido':
 * @property integer $idPedido
 * @property integer $idUsuario
 * @property string $dFechaPedido
 * @property integer $idEstado
 * @property string $sObservaciones
 * @property double $nCobrado
 * @property integer $idSucursal
 * @property integer $idUsuarioAlta
 * @property double $nFaltante
 * @property string $dFechaEntrega
 * @property integer $idPago
 * @property integer $bCompletado
 *
 * The followings are the available model relations:
 * @property Sucursal $sucursal
 * @property Usuario $usuario
 * @property Pago $pago
 * @property Pedidoarticulo[] $articulos
 * @property Pedidodocumento[] $documentos
 * @property Pedidoestado $estado
 */
class Pedido extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pedido';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('idSucursal', 'required'),
			array('idUsuario, idEstado, idSucursal, idUsuarioAlta, idPago, bCompletado', 'numerical', 'integerOnly'=>true),
			array('sObservaciones', 'length', 'max'=>300),
			array('nCobrado, nFaltante', 'numerical'),
			array('dFechaPedido, dFechaEntrega', 'safe'),
			array('idPedido, idUsuario, dFechaPedido, dFechaEntrega, idEstado, idSucursal', 'safe', 'on'=>'search'),
		);
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'sucursal' => array(self::BELONGS_TO, 'Sucursal', 'idSucursal'),
			'usuario' => array(self::BELONGS_TO, 'Usuario', 'idUsuario'),
			'pago' => array(self::BELONGS_TO, 'Pago', 'idPago'),
			'articulos' => array(self::HAS_MANY, 'Pedidoarticulo', 'idPedido'),
			'documentos' => array(self::HAS_MANY, 'Pedidodocumento', 'idPedido'),
			'estado' => array(self::BELONGS_TO, 'Pedidoestado', 'idEstado'),
			'creador' => array(self::BELONGS_TO, 'Usuario', 'idUsuarioAlta'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idPedido' => 'Cód. Pedido',
			'idUsuario' => 'Usuario',
			'dFechaPedido' => 'Creación del Pedido',
			'idEstado' => 'Estado',
			'sObservaciones' => 'Observaciones',
			'nCobrado' => 'Seña',
			'idSucursal' => 'Sucursal de Entrega',
			'idUsuarioAlta' => 'Usuario Creador',
			'nFaltante' => 'Faltante',
			'dFechaEntrega' => 'Fecha de Entrega',
			'idPago' => 'Pago',
			'bCompletado' => 'Completado'
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
		if($this->idUsuario == '0') {
			$criteria->addCondition('idUsuario IS NULL');
		} else {
			$criteria->compare('idUsuario', $this->idUsuario);
		}
		
		if(!empty($this->dFechaPedido)){
			$fechaPedido = date_create_from_format('d/m/Y', $this->dFechaPedido);
			$criteria->compare('DATE_FORMAT(dFechaPedido, "%Y-%m-%d")', $fechaPedido->format('Y-m-d'));
		}

		if(!empty($this->dFechaEntrega)){
			$fechaEntrega = date_create_from_format('d/m/Y', $this->dFechaEntrega);
			$criteria->compare('dFechaEntrega', $fechaEntrega->format('Y-m-d'));
		}

		$criteria->with = array('pago');
		$criteria->together = true;

		$criteria->compare('idEstado',$this->idEstado);
		$criteria->compare('idSucursal',$this->idSucursal);
		$criteria->compare('bCompletado', 1);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
		        'defaultOrder'=>'idPedido DESC'
		    ),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Pedido the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getEstadoGrid()
	{
		switch ($this->idEstado) {
			case 1: // A Confirmar
			case 2: // Impresión
				return TbHtml::muted($this->estado->sDescripcion);
			case 3: // Armado de Pedido
				return TbHtml::em($this->estado->sDescripcion, array('color' => TbHtml::TEXT_COLOR_INFO));
			case 4: // Para Entregar
				return TbHtml::em($this->estado->sDescripcion, array('color' => TbHtml::TEXT_COLOR_WARNING));
			case 5: // Entregado
				return TbHtml::em($this->estado->sDescripcion, array('color' => TbHtml::TEXT_COLOR_SUCCESS));
			case 6: // Cancelado
				return TbHtml::em($this->estado->sDescripcion, array('color' => TbHtml::TEXT_COLOR_ERROR));
			default:
				return TbHtml::muted($this->estado->sDescripcion);
		}
	}

	public function getFaltante(){
		if(isset($this->idPago)) {
			$pago = Pago::model()->with('formaPago')->findByPk($this->idPago);
			$detalle = '';
			if($pago->idFormaPago == 1) {
				$detalle = ' <a rel="tooltip" data-toggle="tooltip" title="" href="#" data-original-title="Pago mediante DineroMail">DM</a>';
			}

			if($pago->bFinalizado) {
				return 'Pago completo' . $detalle;
			}
			$total = $pago->nTotal - $this->nCobrado;
			return '$' . $total . ' - ' . $pago->formaPago->sDescripcion . $detalle;
		} else {
			$this->nFaltante = (float)$this->nFaltante;
			if(isset($this->nFaltante) && $this->nFaltante > 0){
				return '$' . $this->nFaltante;
			} elseif(isset($this->nFaltante) && $this->nFaltante == 0) {
				return 'Pago Completo';
			} else {
				$faltante = 0;
				foreach($this->documentos as $documento) {
	                $faltante += $documento->nValorNeto + $documento->nValorAnillados;
	            }

	            foreach($this->articulos as $articulo) {
	                $faltante += $documento->nValorNeto;
	            }
	            
	            if(($faltante - $this->nCobrado) < 0)
	            	$this->nFaltante = 0;
	            else
	            	$this->nFaltante = $faltante - $this->nCobrado;

	            $this->nFaltante = round($this->nFaltante, 2);

	            $this->save();
	            if($this->nFaltante == 0)
	            	return 'Pago Completo';
	            return '$' . $this->nFaltante;
			}
		}
	}

	public function getTodoDetalleEntregado()
	{
		foreach($this->articulos as $articuloRel) {
            if(!$articuloRel->bEntregado)
            	return false;
        }

        foreach($this->documentos as $documentoRel) {
            if(!$documentoRel->bEntregado)
            	return false;
        }
        return true;
	}

}
