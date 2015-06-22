<?php
/* @var $this ArticuloController */
/* @var $model Articulo */
/* @var $form TbActiveForm */
?>

<div class="form">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'articulo-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
    
    <?php $this->widget( 'ext.EChosen.EChosen' ); ?>

    <p class="help-block">Los campos con <span class="required">*</span> son requeridos.</p>

    <?php echo $form->errorSummary($model); ?>

            <?php echo $form->dropDownListControlGroup($model,'idSucursal', $sucursales, array('span'=>5)); ?>

            <?php echo $form->textFieldControlGroup($model,'sDescripcion',array('span'=>5,'maxlength'=>150)); ?>

            <?php echo $form->textFieldControlGroup($model,'nPrecio',array('span'=>1,'maxlength'=>45, 'prepend' => '$')); ?>

            <?php echo $form->textFieldControlGroup($model,'nStock',array('span'=>1, 'append' => 'unidades')); ?>

            <?php echo $form->textAreaControlGroup($model,'sObservaciones',array('span'=>5,'maxlength'=>300)); ?>

        <div class="form-actions">
        <?php echo TbHtml::submitButton($model->isNewRecord ? 'Crear' : 'Guardar',array(
		    'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
		    'size'=>TbHtml::BUTTON_SIZE_LARGE,
		)); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->