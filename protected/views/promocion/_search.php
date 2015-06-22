<?php
/* @var $this PromocionController */
/* @var $model Promocion */
/* @var $form CActiveForm */
?>

<div class="wide form">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

                    <?php echo $form->textFieldControlGroup($model,'idPromocion',array('span'=>5)); ?>

                    <?php echo $form->textFieldControlGroup($model,'nCantidad',array('span'=>5)); ?>

                    <?php echo $form->textFieldControlGroup($model,'dPrecio',array('span'=>5)); ?>

                    <?php echo $form->textFieldControlGroup($model,'bActivo',array('span'=>5)); ?>

        <div class="form-actions">
        <?php echo TbHtml::submitButton('Buscar',  array('color' => TbHtml::BUTTON_COLOR_PRIMARY,));?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->