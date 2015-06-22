<?php
/* @var $this DocumentoController */
/* @var $model Documento */
/* @var $form CActiveForm */
?>

<div class="wide form">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

                    <?php echo $form->textFieldControlGroup($model,'idDocumento',array('span'=>5)); ?>

                    <?php echo $form->textFieldControlGroup($model,'dFechaAlta',array('span'=>5)); ?>

                    <?php echo $form->textFieldControlGroup($model,'sTitulo',array('span'=>5,'maxlength'=>100)); ?>

                    <?php echo $form->textFieldControlGroup($model,'nStock',array('span'=>5)); ?>

                    <?php echo $form->textFieldControlGroup($model,'bActivo',array('span'=>5)); ?>

                    <?php echo $form->textFieldControlGroup($model,'sSobre',array('span'=>5,'maxlength'=>255)); ?>

                    <?php echo $form->textFieldControlGroup($model,'sObservaciones',array('span'=>5,'maxlength'=>255)); ?>

                    <?php echo $form->textFieldControlGroup($model,'nPaginas',array('span'=>5)); ?>

                    <?php echo $form->textFieldControlGroup($model,'sNombreArchivo',array('span'=>5,'maxlength'=>45)); ?>

                    <?php echo $form->textFieldControlGroup($model,'sAutor',array('span'=>5,'maxlength'=>45)); ?>

        <div class="form-actions">
        <?php echo TbHtml::submitButton('Buscar',  array('color' => TbHtml::BUTTON_COLOR_PRIMARY,));?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->