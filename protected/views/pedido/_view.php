<?php
/* @var $this PedidoController */
/* @var $data Pedido */
?>

<div class="view">

    	<b><?php echo CHtml::encode($data->getAttributeLabel('idPedido')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->idPedido),array('view','id'=>$data->idPedido)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idUsuario')); ?>:</b>
	<?php echo CHtml::encode($data->idUsuario); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dFechaPedido')); ?>:</b>
	<?php echo CHtml::encode(date("d-m-Y H:i:s",strtotime($data->dFechaPedido))); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idEstado')); ?>:</b>
	<?php echo CHtml::encode($data->idEstado); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sObservaciones')); ?>:</b>
	<?php echo CHtml::encode($data->sObservaciones); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nCobrado')); ?>:</b>
	<?php echo CHtml::encode($data->nCobrado); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idSucursal')); ?>:</b>
	<?php echo CHtml::encode($data->idSucursal); ?>
	<br />


</div>