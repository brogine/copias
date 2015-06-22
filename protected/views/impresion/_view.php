<?php
/* @var $this ImpresionController */
/* @var $data Impresion */
?>

<div class="view">

    	<b><?php echo CHtml::encode($data->getAttributeLabel('idImpresion')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->idImpresion),array('view','id'=>$data->idImpresion)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fFecha')); ?>:</b>
	<?php echo CHtml::encode($data->fFecha); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idUsuario')); ?>:</b>
	<?php echo CHtml::encode($data->idUsuario); ?>
	<br />


</div>