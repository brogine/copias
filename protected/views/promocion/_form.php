<?php
/* @var $this PromocionController */
/* @var $model Promocion */
/* @var $form TbActiveForm */
?>

<div class="form">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'promocion-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

    <p class="help-block">Los campos con <span class="required">*</span> son requeridos.</p>

    <?php echo $form->errorSummary($model); ?>

            <?php echo $form->textFieldControlGroup($model,'nCantidad',array('span'=>5)); ?>

            <?php echo TbHtml::i('La cantidad indica: a partir de cu&aacute;ntos elementos empieza a regir la promoci&oacute;n.'); ?>

            <?php echo $form->textFieldControlGroup($model,'dPrecio',array('span'=>5, 'prepend' => '$')); ?>

            <?php echo TbHtml::i('Precio por p&aacute;gina.'); ?>

            <?php 
            if(!$model->isNewRecord):
                echo $form->checkBoxControlGroup($model,'bActivo',array('span'=>9));
                echo TbHtml::i('Si la promoci&oacute;n se encuentra vigente o no.');
            endif;
            ?>
            
    <div class="form-actions">
        <?php echo TbHtml::submitButton($model->isNewRecord ? 'Crear' : 'Guardar',array(
		    'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
		    'size'=>TbHtml::BUTTON_SIZE_LARGE,
		)); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->