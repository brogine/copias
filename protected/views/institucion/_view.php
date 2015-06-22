<?php
/* @var $this InstitucionController */
/* @var $data Institucion */
?>

<div class="view">

    	<b><?php echo CHtml::encode($data->getAttributeLabel('idInstitucion')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->idInstitucion),array('view','id'=>$data->idInstitucion)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sDescripcion')); ?>:</b>
	<?php echo CHtml::encode($data->sDescripcion); ?>
	<br />


</div>