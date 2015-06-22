<?php

$this->pageTitle=Yii::app()->name . ' - Reenviar';

if(Yii::app()->user->hasFlash('success')) {
    echo TbHtml::alert(TbHtml::ALERT_COLOR_SUCCESS, Yii::app()->user->getFlash('success'));
}
else {

    if(Yii::app()->user->hasFlash('error')) {
        echo TbHtml::alert(TbHtml::ALERT_COLOR_ERROR, Yii::app()->user->getFlash('error'));
    }

    echo CHtml::beginForm('', 'post', array('class'=>'form-signin'));

?>

    <h2 class="form-signin-heading">Reenviar Email de confirmaci&oacute;n</h2>

<?php
    echo CHtml::emailField('email', '', array('class'=>'input-block-level', 'placeholder'=>'Ingrese su email'));

?>
    <button class="btn btn-large btn-primary" type="submit">Reenviar</button>

<?php
    echo CHtml::endForm();
}
?>