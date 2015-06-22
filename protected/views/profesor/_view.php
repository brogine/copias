<?php
/* @var $this ProfesorController */
/* @var $data Profesor */
?>

<div class="view">

    	<b><?php echo CHtml::encode($data->getAttributeLabel('idProfesor')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->idProfesor),array('view','id'=>$data->idProfesor)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sNombre')); ?>:</b>
	<?php echo CHtml::encode($data->sNombre); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sApellido')); ?>:</b>
	<?php echo CHtml::encode($data->sApellido); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sTelefono')); ?>:</b>
	<?php echo CHtml::encode($data->sTelefono); ?>
	<br />


</div>