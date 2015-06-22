<?php
/* @var $this ArticuloController */
/* @var $data Articulo */
?>

<div class="view">

    	<b><?php echo CHtml::encode($data->getAttributeLabel('idArticulo')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->idArticulo),array('view','id'=>$data->idArticulo)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idSucursal')); ?>:</b>
	<?php echo CHtml::encode($data->idSucursal); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sDescripcion')); ?>:</b>
	<?php echo CHtml::encode($data->sDescripcion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nPrecio')); ?>:</b>
	<?php echo CHtml::encode($data->nPrecio); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nStock')); ?>:</b>
	<?php echo CHtml::encode($data->nStock); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sObservaciones')); ?>:</b>
	<?php echo CHtml::encode($data->sObservaciones); ?>
	<br />


</div>