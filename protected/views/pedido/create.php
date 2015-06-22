<?php
/* @var $this PedidoController */
/* @var $model Pedido */
?>

<?php
$this->breadcrumbs=array(
	'Pedidos'=>array('index'),
	'Nuevo',
);

$this->menu=array(
	array('label'=>'Administrar Pedidos', 'url'=>array('index'), 'linkOptions' => array('class'=>'muted')),
);
?>

<div class="row">
	<div class="span5">
		<h1>Nuevo Pedido</h1>
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

<?php $this->renderPartial('_form', 
	array(
		'model'=>$model
		, 'usuarios' => $usuarios
		, 'estados' => $estados
		, 'sucursales' => $sucursales
		, 'documentos' => $documentos
        , 'articulos' => $articulos
        , 'valorAnillado' => $valorAnillado
        , 'materias' => $materias
        , 'profesores' => $profesores
        , 'carreras' => $carreras
        , 'cursos' => $cursos
        , 'instituciones' => $instituciones
        , 'filtroProfesores' => $filtroProfesores
        , 'filtroMaterias' => $filtroMaterias
        , 'filtroCarreras' => $filtroCarreras
        , 'filtroCursos' => $filtroCursos
        , 'filtroInstituciones' => $filtroInstituciones)); ?>