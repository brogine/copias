<?php
/* @var $this DocumentoController */
/* @var $data Documento */
?>

<div class="view">

    	<b><?php echo CHtml::encode($data->getAttributeLabel('idDocumento')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->idDocumento),array('view','id'=>$data->idDocumento)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dFechaAlta')); ?>:</b>
	<?php echo CHtml::encode($data->dFechaAlta); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sTitulo')); ?>:</b>
	<?php echo CHtml::encode($data->sTitulo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nStock')); ?>:</b>
	<?php echo CHtml::encode($data->nStock); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bActivo')); ?>:</b>
	<?php echo CHtml::encode($data->bActivo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sSobre')); ?>:</b>
	<?php echo CHtml::encode($data->sSobre); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sObservaciones')); ?>:</b>
	<?php echo CHtml::encode($data->sObservaciones); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('nPaginas')); ?>:</b>
	<?php echo CHtml::encode($data->nPaginas); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sNombreArchivo')); ?>:</b>
	<?php echo CHtml::encode($data->sNombreArchivo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sAutor')); ?>:</b>
	<?php echo CHtml::encode($data->sAutor); ?>
	<br />

	*/ ?>

</div>

En el detalle: fecha de creación, institución, carrera, materia, profesor, curso, autor, sobre el libro y el resto de los campos.