<?php
/* @var $this DocumentoController */
/* @var $model Documento */
?>

<?php
$this->breadcrumbs=array(
	'Documentos'=>array('index'),
	'Nuevo',
);

$this->menu=array(
	array('label'=>'Administrar Documentos', 'url'=>array('index'), 'linkOptions' => array('class'=>'muted')),
);
?>

<div class="row">
	<div class="span5">
		<h1>Nuevo Documento</h1>
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