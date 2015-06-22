<?php
/* @var $this CarreraController */
/* @var $data Carrera */
?>

<div class="view">

    	<b><?php echo CHtml::encode($data->getAttributeLabel('idCarrera')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->idCarrera),array('view','id'=>$data->idCarrera)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sDescripcion')); ?>:</b>
	<?php echo CHtml::encode($data->sDescripcion); ?>
	<br />


</div>