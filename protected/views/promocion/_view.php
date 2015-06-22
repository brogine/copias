<?php
/* @var $this PromocionController */
/* @var $data Promocion */
?>

<div class="view">

    	<b><?php echo CHtml::encode($data->getAttributeLabel('idPromocion')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->idPromocion),array('view','id'=>$data->idPromocion)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nCantidad')); ?>:</b>
	<?php echo CHtml::encode($data->nCantidad); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dPrecio')); ?>:</b>
	<?php echo CHtml::encode($data->dPrecio); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bActivo')); ?>:</b>
	<?php echo CHtml::encode($data->bActivo); ?>
	<br />


</div>