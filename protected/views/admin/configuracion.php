<div class="form">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'configuracion-form',
	'enableAjaxValidation'=>false,
)); ?>

	<h1>Configuraci&oacute;n</h1>

	<?php
	if(Yii::app()->user->hasFlash('error')) {
        echo TbHtml::alert(TbHtml::ALERT_COLOR_ERROR, Yii::app()->user->getFlash('error'));
    }
    ?>

	<div class="row">
    	<div class="span12">
	<?php  
	foreach ($data as $data):
	?>
		<div class="control-group">
			<label class="control-label" for="Configuracion_sValue<?=$data->idConfiguracion?>"><?=$data->sUserKey?></label>
			<div class="controls">
				<input maxlength="255" name="Configuracion[<?=$data->idConfiguracion?>][sValue]" id="Configuracion_sValue<?=$data->idConfiguracion?>" class="span6" type="text" value="<?=$data->sValue?>">
			</div>
		</div>
	<?php
	endforeach;
	?>

		</div>
    </div>

	<div class="form-actions">
	    <?php echo TbHtml::submitButton('Guardar',array(
	        'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
	        'size'=>TbHtml::BUTTON_SIZE_LARGE,
	    )); ?>
	    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->