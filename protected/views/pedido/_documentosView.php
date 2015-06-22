<?php 
$this->widget('bootstrap.widgets.TbGridView', array(
   'dataProvider' => $dataProvider,
   'id'=>'documentosList',
   'columns'=>array(
        array(
          'name'=>'idDocumento',
          'header'=>'#',
        ),
        'sTitulo',
        'nPaginas',
        'sAutor',
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
        ),
    ),
)); ?>