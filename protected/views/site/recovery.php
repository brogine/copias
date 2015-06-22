<?php
/* @var $this SiteController */
/* @var $model RecoveryForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Recuperar';

if(Yii::app()->user->hasFlash('success')) {
    echo TbHtml::alert(TbHtml::ALERT_COLOR_SUCCESS, Yii::app()->user->getFlash('success'));
} elseif (Yii::app()->user->hasFlash('error')) {
	echo TbHtml::alert(TbHtml::ALERT_COLOR_WARNING, Yii::app()->user->getFlash('error'));
}

$form=$this->beginWidget('CActiveForm', array('htmlOptions'=>array('class'=>'form-signin'))); ?>
 
    <?php echo $form->errorSummary($model); ?>

	<h2 class="form-signin-heading">Recuperar Password</h2>

	<?php 
	if($model->scenario == 'recovery'){
		echo $form->labelEx($model, 'email');
		echo $form->emailField($model, 'email', array('class'=>'input-block-level', 'placeholder'=>'Ingrese su email'));
	}
	elseif($model->scenario == 'reset') {
		echo $form->labelEx($model, 'newPassword');
		echo $form->passwordField($model,'newPassword', array('class'=>'input-block-level', 'autocomplete'=>'off'));
		echo $form->labelEx($model, 'newVerify');
		echo $form->passwordField($model,'newVerify', array('class'=>'input-block-level', 'autocomplete'=>'off'));
		echo $form->hiddenField($model, 'idUsuario');
	}
	?>

	<button class="btn btn-large btn-primary" type="submit">Recuperar</button>

<?php 
$this->endWidget();
?>