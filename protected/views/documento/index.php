<?php
/* @var $this DocumentoController */
/* @var $model Documento */


$this->breadcrumbs=array(
	'Documentos'=>array('index'),
	'Administrar',
);

$this->menu=array(
	array('label'=>'Nuevo Documento', 'url'=>array('nuevo'), 'linkOptions' => array('class'=>'text-success')),
);

?>

<div class="row">
	<div class="span5">
		<h1>Administrar Documentos</h1>
	</div>
	<div class="span7">
		<?php
		$this->widget('zii.widgets.CMenu', array(
            'items'=>$this->menu,
            'htmlOptions'=>array('class'=>'nav nav-pills'),
        ));
        ?>
    </div>
</div>

<p>
	<?php echo TbHtml::labelTb('Importante', array('color' => TbHtml::LABEL_COLOR_IMPORTANT)); ?>
	El stock de los documentos se administra <?php echo TbHtml::b('manualmente.'); ?>
</p>

<?php

$this->widget( 'ext.EChosen.EChosen' );

Yii::app()->clientScript->registerScript('search', "
$('.chosen').chosen().change(function(){
    $.fn.yiiGridView.update('documento-grid', {
      url: '" . Yii::app()->baseUrl . "/documento/index'
    });
    return false;
});
$('#reset').click(function(){
	$('#Documento[idDocumento], #Documento[sTitulo], #Documento[nPaginas], #Documento[sObservaciones]').val('');
    $('#Documento_filtroInstituciones, #Documento_filtroProfesores, #Documento_filtroMaterias, #Documento_filtroCarreras, #Documento_filtroCursos').val('').trigger('chosen:updated');
    $.fn.yiiGridView.update('documento-grid', {
      url: '" . Yii::app()->baseUrl . "/documento/index',
    });
    return false;
});");

?>

<div class="row">
	<div class="span6">

<?php

echo CHtml::label('Buscá por Institución:', 'Documento[filtroInstituciones]');
echo CHtml::dropDownList('Documento[filtroInstituciones]', $filtroInstituciones, $instituciones, array('multiple' => true, 'class'=>'span6 chosen'));

echo CHtml::label('Buscá por Profesor:', 'Documento[filtroProfesores]');
echo CHtml::dropDownList('Documento[filtroProfesores]', $filtroProfesores, $profesores, array('multiple' => true, 'class'=>'span6 chosen'));

echo CHtml::label('Buscá por Materias:', 'Documento[filtroMaterias]');
echo CHtml::dropDownList('Documento[filtroMaterias]', $filtroMaterias, $materias, array('multiple' => true, 'class'=>'span6 chosen'));

?>
	</div>
	<div class="span6">
<?php

echo CHtml::label('Buscá por Carreras:', 'Documento[filtroCarreras]');
echo CHtml::dropDownList('Documento[filtroCarreras]', $filtroCarreras, $carreras, array('multiple' => true, 'class'=>'span6 chosen'));

echo CHtml::label('Buscá por Cursos:', 'Documento[filtroCursos]');
echo CHtml::dropDownList('Documento[filtroCursos]', $filtroCursos, $cursos, array('multiple' => true, 'class'=>'span6 chosen'));

?>
<label>&nbsp;</label>
<a href="javascript:void(0)" class="btn btn-primary" id="reset">Nueva B&uacute;squeda</a>

	</div>
</div>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'documento-grid',
	'dataProvider'=>$model->searchTextLive(),
	'filter'=>$model,
	'rowCssClassExpression' => '$data->getCssClass()',
	'beforeAjaxUpdate' => "js:function(id, options) { options.data = $('#Documento_filtroInstituciones, #Documento_filtroProfesores, #Documento_filtroMaterias, #Documento_filtroCarreras, #Documento_filtroCursos, #Documento[idDocumento], #Documento[sTitulo], #Documento[nPaginas], #Documento[sObservaciones]').serialize(); }",
	'columns'=>array(
		array(
            'name'=>'idDocumento',
            'htmlOptions'=>array('class'=>'coddoc')
        ),
		'sTitulo',
		'nPaginas',
		array(
        	'name'=>'dFechaAlta',
        	'type'=>'fechaHora',
        	'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model'=>$model, 
                'name'=>'dFechaAlta', 
                'language' => 'es',
                'htmlOptions' => array(
                    'id' => 'datepicker_for_dFechaAlta',
                    'size' => '10',
                ),
                'defaultOptions' => array(
                    'showOn' => 'focus', 
                    'dateFormat' => 'dd-mm-yy',
                    'showOtherMonths' => true,
                    'selectOtherMonths' => true,
                    'changeMonth' => true,
                    'changeYear' => true,
                    'showButtonPanel' => true
                )
            ), true),
        ),
		array(
			'name'=>'price',
			'type'=>'currency',
			'filter'=>''
		),
		'sObservaciones',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{view} {file} {update} {delete}',
			'buttons'=>array(
				'file'=> array(
		            'options' => array('rel' => 'tooltip', 'data-toggle' => 'tooltip', 'title' => 'Archivo', 'target'=>'_blank'),
	                'label' => '<i class="icon-file"></i>',
	                'imageUrl' => false,
	                'url' => '$data->physicalLocation',
		        ),
			)
		),
	),
)); ?>