<?php
/* @var $this SucursalController */
/* @var $data Sucursal */
?>

<div class="view">

    	<b><?php echo CHtml::encode($data->getAttributeLabel('idSucursal')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->idSucursal),array('view','id'=>$data->idSucursal)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sNombreSucursal')); ?>:</b>
	<?php echo CHtml::encode($data->sNombreSucursal); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sDomicilio')); ?>:</b>
	<?php echo CHtml::encode($data->sDomicilio); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sTelefono')); ?>:</b>
	<?php echo CHtml::encode($data->sTelefono); ?>
	<br />


</div>