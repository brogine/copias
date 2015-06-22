<?php
/* @var $this RolController */
/* @var $data Rol */
?>

<div class="view">

    	<b><?php echo CHtml::encode($data->getAttributeLabel('idRol')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->idRol),array('view','id'=>$data->idRol)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sDescripcion')); ?>:</b>
	<?php echo CHtml::encode($data->sDescripcion); ?>
	<br />


</div>