<?php
/* @var $this MateriaController */
/* @var $data Materia */
?>

<div class="view">

    	<b><?php echo CHtml::encode($data->getAttributeLabel('idMateria')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->idMateria),array('view','id'=>$data->idMateria)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sDescripcion')); ?>:</b>
	<?php echo CHtml::encode($data->sDescripcion); ?>
	<br />


</div>