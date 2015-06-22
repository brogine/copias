<?php

echo TbHtml::labelTb('Detalles del pedido', array('color' => TbHtml::LABEL_COLOR_INFO));

$this->widget('yiiwheels.widgets.grid.WhGroupGridView', array(
	'id'=>'detalle'.$id,
	'type' => 'striped bordered',
	'dataProvider' => $data,
	'pagerCssClass'=>'pagination pagchild',
	'extraRowColumns' => array('tipo'),
	'extraRowHtmlOptions' => array('style' => 'font-size:14px;padding:10px;color:red;'),
	'columns' => array(
		array('header'=>'Cód.', 'name'=>'cod'),
		array('name'=>'desc', 'header'=>'Descripcion'),
		array('name'=>'cant', 'header'=>'Cantidad'),
		'precio:currency',
		array('header'=>'Subtotal', 'value'=>'"$".$data["subtotal"]'),
		array(
			'class' => 'editable.EditableColumn',
			'editable' => array(
				'type'      => 'select',
				'url'       => $this->createUrl('pedido/updatePedido'),
				'source'    => $this->createUrl('pedido/getEstadosDocumentos'),
				'apply'		=> '$data["estado"] == 1 && $data["tipo"] == "Documento"',
            ),
			'name'=>'estado',
			'header'=>'Estado',
			'value'=>'$data["estado"] == 2 ? "Impreso" : ($data["estado"] != false ? "En Proceso" : "-")'
		),
		array('header'=>'Anillados', 'name'=>'anill'),
		array(
			'class' => 'editable.EditableColumn',
			'editable' => array(
				'apply'		=> '$data["ent"] == 0',
				'type'      => 'checklist',
				'url'       => $this->createUrl('pedido/updatePedidoDetalle'),
				'source'    => "{'1': 'Entregado'}",
            ),
			'header'=>'Entregado', 
			'name'=>'ent',
			'value'=>'$data["ent"] ? "Si" : "No Entregado"'
		)
	),
)); ?>