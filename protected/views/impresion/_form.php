<?php
/* @var $this ImpresionController */
/* @var $model Impresion */
/* @var $form TbActiveForm */
?>

<div class="form">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'impresion-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

    <p class="help-block">Chequee los items que vaya a imprimir y presione el bot&oacute;n crear.</p>

    <?php echo $form->errorSummary($model); ?>

            <table class="table table-hover table-bordered table-condensed" id="impresion-detalle" name="impresion-detalle">
                <thead>
                    <tr>
                        <th>Index</th>
                        <th></th>
                        <th>C&oacute;d. Documento</th>
                        <th>Cod. Pedido</th>
                        <th>Cant. Copias</th>
                        <th>Cant. Anillados</th>
                        <th>Cant. P&aacute;ginas</th>
                        <th>Sucursal donde se entrega</th>
                        <th>Sucursal donde se imprime</th>
                        <th>Observaciones</th>
                        <th>Detalle</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

            <?php 
            if(!$model->isNewRecord)
            {
                ?>
                <div class="control-group">
                    <?php echo $form->labelEx($model,'fFecha', array('class'=>'control-label')); ?>
                    <div class="controls">
                        <span class="span5"><b><?=$model->fFecha?></b></span>
                    </div>
                </div>
                <br>

                <div class="control-group">
                    <?php echo $form->labelEx($model,'idUsuario', array('class'=>'control-label')); ?>
                    <div class="controls">
                        <span class="span5"><b><?=$model->usuario->fullName?></b></span>
                    </div>
                </div>
                <br>
            <?php
            }
            ?>
        
        <?php if ($model->isNewRecord): ?>
        <div class="form-actions">
        <?php echo TbHtml::submitButton($model->isNewRecord ? 'Crear' : 'Guardar',array(
		    'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
		    'size'=>TbHtml::BUTTON_SIZE_LARGE,
		)); ?>
        <?php endif; ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->

<?php $this->widget('bootstrap.widgets.TbModal', array(
    'id' => 'idModal',
    'header' => 'Detalle del documento',
    'content' => '',
    'footer' => array(
        TbHtml::button('Cerrar', array('data-dismiss' => 'modal')),
     ),
)); ?>