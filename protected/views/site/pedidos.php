<?php
$this->breadcrumbs=array(
    'Aquí puedes ver tus pedidos realizados. Sitúa el cursor sobre el estado para más información.',
);
?>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
    'id'=>'pedido-grid',
    'dataProvider'=>$pedidos->search(),
    'columns'=>array(
        'idPedido',
        'dFechaPedido',
        array(
            'name' => 'idEstado',
            'header'=>'Estado',
            'type'=>'raw',
            'value'=>'TbHtml::tooltip($data->estado->sDescripcion, "#", $data->estado->sObservacion)',
        ),
        'sObservaciones',
        array(
            'name' => 'idSucursal',
            'header'=>'Sucursal donde retira',
            'value'=>'(isset($data->sucursal) ? $data->sucursal->fullName : "")',
        ),
    ),
)); ?>