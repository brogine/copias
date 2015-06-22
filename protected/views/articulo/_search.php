<?php
/* @var $this ArticuloController */
/* @var $model Articulo */
/* @var $form CActiveForm */
?>

<div class="wide form">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

                    <?php echo $form->textFieldControlGroup($model,'idArticulo',array('span'=>5)); ?>

                    <?php echo $form->textFieldControlGroup($model,'idSucursal',array('span'=>5)); ?>

                    <?php echo $form->textFieldControlGroup($model,'sDescripcion',array('span'=>5,'maxlength'=>150)); ?>

                    <?php echo $form->textFieldControlGroup($model,'nPrecio',array('span'=>5,'maxlength'=>45)); ?>

                    <?php echo $form->textFieldControlGroup($model,'nStock',array('span'=>5)); ?>

                    <?php echo $form->textFieldControlGroup($model,'sObservaciones',array('span'=>5,'maxlength'=>300)); ?>

        <div class="form-actions">
        <?php echo TbHtml::submitButton('Buscar',  array('color' => TbHtml::BUTTON_COLOR_PRIMARY,));?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->