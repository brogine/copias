<?php

echo TbHtml::labelTb('Pedidos que se incluyen', array('color' => TbHtml::LABEL_COLOR_INFO));

$this->widget('yiiwheels.widgets.grid.WhGroupGridView', array(
	'type' => 'striped bordered',
	'dataProvider' => $data,
	'template' => "{items}",
	'columns' => array(
		array('name'=>'id', 'header'=>'Num. Pedido'),
		array('name'=>'copias', 'header'=>'Copias'),
		array('name'=>'fecha', 'header'=>'Fecha'),
		array('name'=>'cliente', 'header'=>'Cliente'),
		array('name'=>'estado', 'header'=>'Estado'),
		array('name'=>'sucursal', 'header'=>'Sucursal'),
	),
)); ?>