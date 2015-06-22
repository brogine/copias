<?php
/* @var $this DocumentoController */
/* @var $model Documento */
?>

<?php
$this->breadcrumbs=array(
	'Documentos'=>array('index'),
	$model->idDocumento,
);

$this->menu=array(
	array('label'=>'Administrar Documento', 'url'=>array('index'), 'linkOptions' => array('class'=>'muted')),
	array('label'=>'Nuevo Documento', 'url'=>array('nuevo'), 'linkOptions' => array('class'=>'text-success')),
	array('label'=>'Editar Documento', 'url'=>array('editar', 'id'=>$model->idDocumento), 'linkOptions' => array('class'=>'text-warning')),
	array('label'=>'Eliminar Documento', 'url'=>'#', 'linkOptions'=>array('class'=>'text-error', 'submit'=>array('eliminar','id'=>$model->idDocumento),'confirm'=>'Seguro desea eliminar este item?')),
);
?>

<div class="row">
	<div class="span5">
		<h1>Ver Documento #<?php echo $model->idDocumento . ' - ' . $model->sTitulo; ?></h1>
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

<?php $this->widget('zii.widgets.CDetailView',array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-condensed table-hover',
    ),
    'data'=>$model,
    'attributes'=>array(
		'idDocumento',
		'sTitulo',
		'nPaginas',
		'price:currency',
		'sObservaciones',
		'dFechaAlta:fechaHora',
		'sAutor',
		'sSobre',
		'nStock',
		'bActivo:bool',
		array('name'=>'sNombreArchivo', 'type'=>'raw', 'value' =>"<a href=\"".$model->physicalLocation."\" target='_blank'>".$model->sNombreArchivo."</a>", 'label' => 'Nombre de archivo'),
	),
)); ?>

<div class="row-fluid">
<?php
if(count($model->instituciones) > 0)
{
	echo "<div class=\"span4\"><p><span class=\"label\">Instituciones:</span><ul>";
	foreach ($model->instituciones as $inst) {
		echo "<li>" . $inst->sDescripcion . "</li>";
	}
	echo "</ul></p></div>";
}
if(count($model->materias) > 0)
{
	echo "<div class=\"span4\"><p><span class=\"label\">Materias:</span><ul>";
	foreach ($model->materias as $mat) {
		echo "<li>" . $mat->sDescripcion . "</li>";
	}
	echo "</ul></p></div>";
}
if(count($model->profesors) > 0)
{
	echo "<div class=\"span4\"><p><span class=\"label\">Profesores:</span><ul>";
	foreach ($model->profesors as $profe) {
		echo "<li>" . $profe->fullName . "</li>";
	}
	echo "</ul></p></div>";
}
if(count($model->carreras) > 0)
{
	echo "<div class=\"span4\"><p><span class=\"label\">Carreras:</span><ul>";
	foreach ($model->carreras as $car) {
		echo "<li>" . $car->sDescripcion . "</li>";
	}
	echo "</ul></p></div>";
}
if(count($model->cursos) > 0)
{
	echo "<div class=\"span4\"><p><span class=\"label\">Cursos:</span><ul>";
	foreach ($model->cursos as $cur) {
		echo "<li>" . $cur->sDescripcion . "</li>";
	}
	echo "</ul></p></div>";
}
?>
</div>