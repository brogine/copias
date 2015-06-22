<?php
/* @var $this UsuarioController */
/* @var $model Usuario */
/* @var $form CActiveForm */
?>

<div class="form">

<?php

if(Yii::app()->user->hasFlash('success')) {
    echo TbHtml::alert(TbHtml::ALERT_COLOR_SUCCESS, Yii::app()->user->getFlash('success'));
} elseif (Yii::app()->user->hasFlash('error')) {
	echo TbHtml::alert(TbHtml::ALERT_COLOR_WARNING, Yii::app()->user->getFlash('error'));
} else {

$form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'usuario-register-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('class'=>'form-signin')
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<h2 class="form-signin-heading">Registrate</h2>

	<p class="help-block">Los campos con <span class="required">*</span> son requeridos.</p>

	<?php echo $form->textFieldControlGroup($model,'sNombre',array('span'=>10,'maxlength'=>50)); ?>

	<?php echo $form->textFieldControlGroup($model,'sApellido',array('span'=>10,'maxlength'=>50)); ?>

	<?php echo $form->textFieldControlGroup($model,'nDocumento',array('span'=>10)); ?>

	<?php /*echo $form->dropDownListControlGroup($model,'idRol', $roles, array('span'=>10, 'empty'=>'Elija un rol'));*/ ?>

    <?php echo $form->textFieldControlGroup($model,'nMatricula',array('span'=>10)); ?>

	<?php echo $form->emailFieldControlGroup($model,'sEmail',array('span'=>10,'maxlength'=>100)); ?>

	<?php echo $form->passwordFieldControlGroup($model,'newPassword',array('span'=>10,'minlength'=>4)); ?>

	<?php echo $form->passwordFieldControlGroup($model,'rePassword',array('span'=>10,'minlength'=>4)); ?>

	<?php echo $form->textFieldControlGroup($model,'sTelefono',array('span'=>10,'maxlength'=>75)); ?>

	<?php echo $form->textFieldControlGroup($model,'sDomicilio',array('span'=>10,'maxlength'=>75)); ?>

    <?php echo $form->dropDownListControlGroup($model,'idCarrera', $carreras, array('span'=>10, 'empty'=>'Elija una carrera')); ?>

	<div class="form-actions">
        <?php echo TbHtml::submitButton('Registrate',array(
		    'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
		    'size'=>TbHtml::BUTTON_SIZE_LARGE,
		)); ?>
	</div>

<?php 

$this->endWidget();

}

?>

</div><!-- form -->