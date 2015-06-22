<?php
$this->breadcrumbs=array(
    'Aquí puedes administrar tu información personal.',
);
?>

<div class="form">

    <h1>Mis Datos</h1>

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'usuario-form',
        'enableAjaxValidation'=>false,
    )); ?>

    <p class="help-block">Los campos con <span class="required">*</span> son requeridos.</p>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->textFieldControlGroup($model,'sNombre',array('span'=>5,'maxlength'=>50)); ?>

    <?php echo $form->textFieldControlGroup($model,'sApellido',array('span'=>5,'maxlength'=>50)); ?>

    <?php echo $form->emailFieldControlGroup($model,'sEmail',array('span'=>5,'maxlength'=>100)); ?>

    <?php echo $form->passwordFieldControlGroup($model,'newPassword',array('span'=>5,'maxlength'=>32)); ?>

    <?php echo $form->passwordFieldControlGroup($model,'rePassword',array('span'=>5,'maxlength'=>32)); ?>

    <?php echo $form->textFieldControlGroup($model,'sTelefono',array('span'=>5)); ?>

    <?php echo $form->textFieldControlGroup($model,'sDomicilio',array('span'=>5,'maxlength'=>75)); ?>

    <div class="form-actions">
        <?php echo TbHtml::submitButton('Guardar',array(
            'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
            'size'=>TbHtml::BUTTON_SIZE_LARGE,
        )); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->