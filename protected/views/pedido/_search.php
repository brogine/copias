<?php
/* @var $this PedidoController */
/* @var $model Pedido */
/* @var $form CActiveForm */
?>

<div class="wide form">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

                    <?php echo $form->textFieldControlGroup($model,'idPedido',array('span'=>5)); ?>

                    <?php echo $form->textFieldControlGroup($model,'idUsuario',array('span'=>5)); ?>

                    <?php echo $form->textFieldControlGroup($model,'dFechaPedido',array('span'=>5)); ?>

                    <?php echo $form->textFieldControlGroup($model,'idEstado',array('span'=>5)); ?>

                    <?php echo $form->textFieldControlGroup($model,'sObservaciones',array('span'=>5,'maxlength'=>300)); ?>

                    <?php echo $form->textFieldControlGroup($model,'idSucursal',array('span'=>5)); ?>

        <div class="form-actions">
        <?php echo TbHtml::submitButton('Buscar',  array('color' => TbHtml::BUTTON_COLOR_PRIMARY,));?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->