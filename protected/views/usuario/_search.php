<?php
/* @var $this UsuarioController */
/* @var $model Usuario */
/* @var $form CActiveForm */
?>

<div class="wide form">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

                    <?php echo $form->textFieldControlGroup($model,'idUsuario',array('span'=>5)); ?>

                    <?php echo $form->textFieldControlGroup($model,'sNombre',array('span'=>5,'maxlength'=>50)); ?>

                    <?php echo $form->textFieldControlGroup($model,'sApellido',array('span'=>5,'maxlength'=>50)); ?>

                    <?php echo $form->textFieldControlGroup($model,'sEmail',array('span'=>5,'maxlength'=>100)); ?>

                    <?php echo $form->textFieldControlGroup($model,'sPassword',array('span'=>5,'maxlength'=>32)); ?>

                    <?php echo $form->textFieldControlGroup($model,'sTelefono',array('span'=>5)); ?>

                    <?php echo $form->textFieldControlGroup($model,'sDomicilio',array('span'=>5,'maxlength'=>75)); ?>

                    <?php echo $form->textFieldControlGroup($model,'bActivo',array('span'=>5)); ?>

                    <?php echo $form->textFieldControlGroup($model,'dUltimoLogin',array('span'=>5)); ?>

                    <?php echo $form->textFieldControlGroup($model,'idRol',array('span'=>5)); ?>

        <div class="form-actions">
        <?php echo TbHtml::submitButton('Buscar',  array('color' => TbHtml::BUTTON_COLOR_PRIMARY,));?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->