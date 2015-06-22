<?php
/* @var $this DocumentoController */
/* @var $model Documento */
/* @var $form TbActiveForm */
?>

<div class="form">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'documento-form',
	'enableAjaxValidation'=>false,
)); ?>

    <?php $this->widget( 'ext.EChosen.EChosen' ); ?>

    <p class="help-block">Los campos con <span class="required">*</span> son requeridos.</p>
    <div class="row">
        <div class="span6">
            <?php echo $form->errorSummary($model); ?>

            <?php echo $form->textFieldControlGroup($model,'sTitulo',array('span'=>6,'maxlength'=>500)); ?>

            <?php echo $form->textFieldControlGroup($model,'sAutor',array('span'=>6,'maxlength'=>45)); ?>

            <?php echo $form->textAreaControlGroup($model,'sSobre',array('span'=>6,'maxlength'=>255)); ?>

            <?php echo $form->textAreaControlGroup($model,'sObservaciones',array('span'=>6,'maxlength'=>255)); ?>

            <?php echo $form->textFieldControlGroup($model,'nPaginas',array('span'=>1, 'append' => 'páginas')); ?>

            <?php echo $form->textFieldControlGroup($model,'nPrecioEspecial',array('span'=>1,'maxlength'=>45, 'prepend' => '$')); ?>

            <?php 
            if(!$model->isNewRecord):
                echo $form->uneditableFieldControlGroup($model,'sNombreArchivo',array('span'=>6,'maxlength'=>45));
            endif;
            ?>

            <?php echo $form->textFieldControlGroup($model,'nStock',array('span'=>1, 'append' => 'unidades')); ?>

        </div>

        <div class="span6">
            <?php

            echo $form->dropDownListControlGroup($model,'institucionesRelated', $instituciones, array('span'=>6,'multiple' => true, 'class'=>'chosen'));

            echo $form->dropDownListControlGroup($model,'profesoresRelated', $profesores, array('span'=>6,'multiple' => true, 'class'=>'chosen'));
        
            echo $form->dropDownListControlGroup($model,'materiasRelated', $materias, array('span'=>6,'multiple' => true, 'class'=>'chosen'));
            
            echo $form->dropDownListControlGroup($model,'carrerasRelated', $carreras, array('span'=>6,'multiple' => true, 'class'=>'chosen'));
        
            echo $form->dropDownListControlGroup($model,'cursosRelated', $cursos, array('span'=>6,'multiple' => true, 'class'=>'chosen'));
            
            ?>

            <?php echo $form->checkBoxControlGroup($model,'bActivo',array('span'=>6)); ?>

            <div class="form-actions">
            <?php echo TbHtml::submitButton($model->isNewRecord ? 'Crear' : 'Guardar',array(
                'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
                'size'=>TbHtml::BUTTON_SIZE_LARGE,
            )); ?>
            </div>
        </div>
    </div>
    <?php $this->endWidget(); ?>

</div><!-- form -->