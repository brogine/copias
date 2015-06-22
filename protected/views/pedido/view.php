<?php
/* @var $this PedidoController */
/* @var $model Pedido */
?>

<?php
$this->breadcrumbs=array(
	'Pedidos'=>array('index'),
	$model->idPedido,
);

$this->menu=array(
	array('label'=>'Administrar Pedidos', 'url'=>array('index'), 'linkOptions' => array('class'=>'muted')),
	array('label'=>'Nuevo Pedido', 'url'=>array('nuevo'), 'linkOptions' => array('class'=>'text-success')),
	array('label'=>'Editar Pedido', 'url'=>array('editar', 'id'=>$model->idPedido), 'linkOptions' => array('class'=>'text-warning')),
	array('label'=>'Eliminar Pedido', 'url'=>'#', 'linkOptions'=>array('class'=>'text-error', 'submit'=>array('eliminar','id'=>$model->idPedido),'confirm'=>'Seguro desea eliminar este item?')),
);
?>

<div class="row">
    <div class="span5">
        <h1>Ver Pedido #<?php echo $model->idPedido; ?></h1>
    </div>
    <div class="span7">
        <?php
        $this->widget('zii.widgets.CMenu', array(
            'items'=>$this->menu,
            'htmlOptions'=>array('class'=>'nav nav-pills'),
        ));
        ?>
    </div>
</div>

<?php $this->widget('zii.widgets.CDetailView',array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-condensed table-hover',
    ),
    'data'=>$model,
    'attributes'=>array(
		'idPedido',
		array('value' => (isset($model->usuario) ? $model->usuario->fullName : "Cliente Mostrador"), 'label' => 'Usuario'),
		'dFechaPedido',
		array('value' => $model->estado->sDescripcion, 'label' => 'Estado'),
		'sObservaciones',
		array('value' => $model->sucursal->sNombreSucursal, 'label' => 'Sucursal'),
		array('value' => (isset($model->creador) ? $model->creador->fullName : $model->usuario->fullName), 'label' => 'Usuario Creador'),
	),
)); ?>

<a class="btn btn-inverse" target="_blank" href="<?=Yii::app()->createUrl('/pedido/comprobante', array('id'=>$model->idPedido))?>">Ver Tal&oacute;n de Encargue</a>
<a class="btn btn-primary" id="print" href="">Imprimir Tal&oacute;n de Encargue</a>

<iframe src="<?=$this->createUrl('/pedido/comprobante', array('id'=>$model->idPedido))?>" id="pdfToPrint" style="display:none"></iframe>

<?php
if(isset($this->idPago) || 1 == 0) {
    ?>
    <a class="btn btn-inverse" target="_blank" href="<?=Yii::app()->createUrl('/pedido/estadoPago', array('id'=>$model->idPedido))?>">Ver estado pago DineroMail</a>
    <?php
}
?>