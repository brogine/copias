<?php
/* @var $this CursoController */
/* @var $data Curso */
?>

<div class="view">

    	<b><?php echo CHtml::encode($data->getAttributeLabel('idCurso')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->idCurso),array('view','id'=>$data->idCurso)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sDescripcion')); ?>:</b>
	<?php echo CHtml::encode($data->sDescripcion); ?>
	<br />


</div>