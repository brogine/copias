<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';

$form=$this->beginWidget('CActiveForm', array('htmlOptions'=>array('class'=>'form-signin')));

    if(Yii::app()->user->hasFlash('success')) {
        echo TbHtml::alert(TbHtml::ALERT_COLOR_SUCCESS, Yii::app()->user->getFlash('success'));
    }
    if(Yii::app()->user->hasFlash('error')) {
        echo TbHtml::alert(TbHtml::ALERT_COLOR_ERROR, Yii::app()->user->getFlash('error'));
    }
 
    echo $form->errorSummary($model); ?>

	<h2 class="form-signin-heading">Ingresar</h2>
	<?php echo $form->label($model,'username'); ?>
	<?php echo $form->emailField($model,'username', array('class'=>'input-block-level', 'placeholder'=>'Email')) ?>
	<?php echo $form->label($model,'password'); ?>
	<?php echo $form->passwordField($model,'password', array('class'=>'input-block-level', 'placeholder'=>'Password')) ?>

	<label class="checkbox">
		<?php echo $form->checkBox($model,'rememberMe') ?> Recordarme
	</label>

	<?php echo CHtml::link('Olvidaste tu contrase&ntilde;a?', array('site/recuperar')); ?> | <?php echo CHtml::link('No tenes cuenta?', array('site/register')); ?> | <?php echo CHtml::link('No te lleg&oacute; el email para confirmar tu cuenta?', array('site/reenviar')); ?>

	<button class="btn btn-large btn-primary" type="submit">Ingresar</button>

<?php $this->endWidget(); ?>