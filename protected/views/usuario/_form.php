<?php
/* @var $this UsuarioController */
/* @var $model Usuario */
/* @var $form TbActiveForm */
?>

<div class="form">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'usuario-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

    <p class="help-block">Los campos con <span class="required">*</span> son requeridos.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="span6">

            <?php echo $form->textFieldControlGroup($model,'sNombre',array('span'=>5,'maxlength'=>50)); ?>

            <?php echo $form->textFieldControlGroup($model,'sApellido',array('span'=>5,'maxlength'=>50)); ?>

            <?php echo $form->emailFieldControlGroup($model,'sEmail',array('span'=>5,'maxlength'=>100)); ?>

            <?php echo $form->passwordFieldControlGroup($model,'newPassword',array('span'=>5,'maxlength'=>32)); ?>

            <?php echo $form->passwordFieldControlGroup($model,'rePassword',array('span'=>5,'maxlength'=>32)); ?>

            <?php echo $form->textFieldControlGroup($model,'sTelefono',array('span'=>5)); ?>

            <?php echo $form->textFieldControlGroup($model,'sDomicilio',array('span'=>5,'maxlength'=>75)); ?>

            <?php echo $form->textFieldControlGroup($model,'nDocumento',array('span'=>5)); ?>

            <?php echo $form->textFieldControlGroup($model,'nMatricula',array('span'=>5)); ?>

            <?php echo $form->dropDownListControlGroup($model,'idCarrera', $carreras, array('span'=>5, 'empty'=>'Elija una carrera')); ?>
        </div>
        <div class="span6">
            <?php
            if(Yii::app()->user->hasFlash('success')) {
                echo TbHtml::alert(TbHtml::ALERT_COLOR_SUCCESS, Yii::app()->user->getFlash('success'));
            }
            if(Yii::app()->user->hasFlash('error')) {
                echo TbHtml::alert(TbHtml::ALERT_COLOR_ERROR, Yii::app()->user->getFlash('error'));
            }
            ?>

            <?php echo $form->dropDownListControlGroup($model,'idRol', $roles, array('span'=>5)); ?>

            <?php echo $form->checkBoxControlGroup($model,'bActivo'); ?>
            <div class="form-actions">
                <?php echo TbHtml::submitButton($model->isNewRecord ? 'Crear' : 'Guardar',array(
                    'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
                    'size'=>TbHtml::BUTTON_SIZE_LARGE,
                )); ?>
                <?php if(!$model->isNewRecord) {
                    ?>
                    <a class="btn btn-inverse btn-large" href="<?=Yii::app()->createUrl('/usuario/enviarEmailConfirmacion', array('id'=>$model->idUsuario))?>">Enviar Email de confirmaci&oacute;n</a>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->