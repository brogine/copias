<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' - Contactanos';
$this->breadcrumbs=array(
    'Contacto',
);
?>

<div class="span10 offset2">

<?php if(Yii::app()->user->hasFlash('contact')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>

<?php else: ?>

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'contact-form',
        'enableAjaxValidation'=>false,
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
        'htmlOptions'=>array('class'=>'well span10')
    )); ?>
            <div class="span6">
                <?php echo $form->labelEx($model,'name'); ?>
                <?php echo $form->textField($model,'name'); ?>
                <?php echo $form->error($model,'name'); ?>

                <?php echo $form->labelEx($model,'email'); ?>
                <?php echo $form->emailField($model,'email'); ?>
                <?php echo $form->error($model,'email'); ?>

                <?php echo $form->labelEx($model,'subject'); ?>
                <?php echo $form->textField($model,'subject',array('size'=>60,'maxlength'=>128)); ?>
                <?php echo $form->error($model,'subject'); ?>

            </div>

            <div class="span6">
                <?php echo $form->labelEx($model,'body'); ?>
                <?php echo $form->textArea($model,'body',array('rows'=>10, 'class'=>'input-xlarge span12')); ?>
                <?php echo $form->error($model,'body'); ?>
            </div>

            <div class="span12">
                <?php if(CCaptcha::checkRequirements()): ?>
                    <?php echo $form->labelEx($model,'verifyCode'); ?>
                    <?php $this->widget('CCaptcha', array('clickableImage'=>true)); ?>
                    <?php echo $form->textField($model,'verifyCode', array('class'=>'span3')); ?>
                    <?php echo $form->error($model,'verifyCode'); ?>
                <?php endif; ?>

                <?php echo CHtml::submitButton('Enviar', array('class'=>'btn btn-primary pull-right')); ?>
            </div>

    <?php $this->endWidget(); ?>

<?php endif; ?>

</div>