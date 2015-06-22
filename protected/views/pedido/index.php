<?php
/* @var $this PedidoController */
/* @var $model Pedido */


$this->breadcrumbs=array(
	'Pedidos'=>array('index'),
	'Administrar',
);

$this->menu=array(
	array('label'=>'Nuevo Pedido', 'url'=>array('nuevo'), 'linkOptions' => array('class'=>'text-success')),
);

?>

<div class="row">
    <div class="span5">
        <h1>Administrar Pedidos</h1>
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

<?php $this->widget( 'ext.EChosen.EChosen' ); 
if(Yii::app()->user->hasFlash('success')) {
    echo TbHtml::alert(TbHtml::ALERT_COLOR_SUCCESS, Yii::app()->user->getFlash('success'));
}

?>

<?php
Yii::app()->getComponent('yiiwheels')->registerAssetJs('bootstrap-bootbox.min.js');
$this->widget('yiiwheels.widgets.grid.WhGridView', array(
	'id'=>'pedido-grid',
	'type' => 'striped bordered',
	'dataProvider' => $model->search(),
	'filter'=>$model,
	'afterAjaxUpdate' => 'function(id, data){$(".chosen").chosen([]); reinstallDatePicker(id, data);}',
	'columns'=>array(
		array(
			'name'=>'idPedido',
			'header'=>'Num. Pedido'
		),
		array(
			'name' => 'idUsuario',
	        'header'=>'Cliente',
	        'value'=>'(isset($data->usuario) ? $data->usuario->fullName : "Cliente Mostrador")',
	        'filter'=>CHtml::dropDownList('Pedido[idUsuario]', $model->idUsuario, $clientes, array('class'=>'chosen'))
        ),
        array(
        	'name'=>'dFechaPedido',
        	'type'=>'fechaHora',
        	'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model'=>$model, 
                'attribute'=>'dFechaPedido', 
                'language' => 'es',
                'htmlOptions' => array(
                    'id' => 'datepicker_for_dFechaPedido',
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
            ), 
            true),
        ),
        array(
        	'name'=>'dFechaEntrega',
        	'type'=>'fecha',
        	'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model'=>$model, 
                'attribute'=>'dFechaEntrega', 
                'language' => 'es',
                'htmlOptions' => array(
                    'id' => 'datepicker_for_dFechaEntrega',
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
            ), 
            true),
        ),
        array(
            'class' => 'editable.EditableColumn',
            'editable' => array(
                'type'      => 'select',
                'url'       => $this->createUrl('pedido/updatePedidoEstado'),
                'source'    => $estadosEditable,
                'validate'  => 'js: function(value) { var detalle = ""; switch(value) { case "1": case "2": detalle = "no entregado y pend. de impresion"; break; case "3": case "4": detalle = "impreso pero no entregado"; break; case "5": detalle = "impreso y entregado"; break; default: break; } if(!confirm("Todos los documentos del pedido pasarán a " + detalle + ". ¿Desea continuar?")) return "No se aceptó la modificación."; }'
            ),
            'header'=>'Estado', 
            'name'=>'idEstado',
            'filter'=>$estados,
            'value'=>'$data->estadoGrid',
            'type'=>'raw'
        ),
		array(
			'name' => 'idSucursal',
	        'header'=>'Sucursal',
	        'value'=>'(isset($data->sucursal) ? $data->sucursal->sNombreSucursal : "")',
	        'filter'=> $sucursales
        ),
        array(
        	'name'=>'faltante',
        	'header'=>'Saldo faltante',
        	'value'=>'$data->faltante',
            'type'=>'raw',
        	'filter'=>''
        ),
        array(
			'class' => 'yiiwheels.widgets.grid.WhRelationalColumn',
			'header' => 'Detalle',
			'url' => $this->createUrl('pedido/detalleAjaxGrid'),
			'value' => '"ver detalle"',
		),
		array(
            'header'=>'<button id="refresh-button"><i class="icon-repeat"></i></button>',
			'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{view} {update} {delete} {wrap}',
            'buttons'=>array(
                'wrap'=> array(
                    'options' => array('rel' => 'tooltip', 'data-toggle' => 'tooltip', 'title' => 'Armar Pedido'),
                    'label' => '<i class="icon-tags"></i>',
                    'imageUrl' => false,
                    'url' => 'Yii::app()->createUrl("pedido/armar", array("id"=>$data->idPedido))',
                    'visible'=>'$data->idEstado < 3',
                    'click'=>'function(){ if(!confirm("El pedido está por pasarse al estado \"Pedido Armado\", pasando sus documentos a impresos pero no entregados, ¿desea continuar?")) { return false; } return true; }'
                ),
            )
		),
	),
)); 

Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
    $('#datepicker_for_dFechaPedido').datepicker($.datepicker.regional['es']);
    $('#datepicker_for_dFechaEntrega').datepicker($.datepicker.regional['es']);
}
$('body').on('click','#refresh-button',function(e) {
    e.preventDefault();
    $('#pedido-grid').yiiGridView('update');
});");

?>