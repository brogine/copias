<?php
/* @var $this RolController */
/* @var $model Rol */
/* @var $form TbActiveForm */
?>

<div class="form">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'rol-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<div class="row">

	<div class="span6">

    <p class="help-block">Los campos con <span class="required">*</span> son requeridos.</p>

    <?php echo $form->errorSummary($model); ?>

        <?php echo $form->textFieldControlGroup($model,'sDescripcion',array('span'=>5,'maxlength'=>45)); ?>
        <?php echo $form->checkBoxControlGroup($model,'bPermitidoFront',array('span'=>5)); ?>

    <div class="form-actions">
        <?php echo TbHtml::submitButton($model->isNewRecord ? 'Crear' : 'Guardar',array(
		    'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
		    'size'=>TbHtml::BUTTON_SIZE_LARGE,
		)); ?>
    </div>

    </div>

    <div class="span6">

    <h3>Permisos por m&oacute;dulo</h3>

    <?php 

    if(!isset($permisos))
    	$permisos = array();

    echo CHtml::checkBoxList('permiso', $permisos, $modulos, 
    	array('template'=>'<li>{input} {label}</li>', 'separator' => ''));

    ?>

    </div>

    <?php 

    $this->endWidget(); 

    ?>

    </div>

</div><!-- form -->