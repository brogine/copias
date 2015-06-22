<?php
/* @var $this DocumentoController */
/* @var $model Documento */
?>

<?php
$this->breadcrumbs=array(
	'Documentos'=>array('index'),
	$model->idDocumento=>array('view','id'=>$model->idDocumento),
	'Editar',
);

$this->menu=array(
	array('label'=>'Administrar Documento', 'url'=>array('index'), 'linkOptions' => array('class'=>'muted')),
	array('label'=>'Nuevo Documento', 'url'=>array('nuevo'), 'linkOptions' => array('class'=>'text-success')),
	array('label'=>'Ver Documento', 'url'=>array('ver', 'id'=>$model->idDocumento), 'linkOptions' => array('class'=>'text-info')),
);
?>

<div class="row">
	<div class="span5">
		<h1>Editar Documento <?php echo $model->idDocumento; ?></h1>
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

<?php $this->renderPartial('_form', array('model'=>$model, 'instituciones' => $instituciones, 'materias' => $materias, 'profesores' => $profesores, 'carreras' => $carreras, 'cursos' => $cursos)); ?>