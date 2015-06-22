<?php

class ImpresionController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/admin';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
                'expression'=>'Yii::app()->user->hasAccessTo(Permiso::MODULO_IMPRESION)',
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionNuevo()
	{
		$model=new Impresion;

		if (isset($_POST) && isset($_POST['impresion_det'])) {
			$model->idUsuario = Yii::app()->user->id;
			$model->fFecha = new CDbExpression('NOW()');

			if ($model->save()) {
				foreach ($_POST['impresion_det'] as $detalle) {
					// $detalle[0] -> idDocumento
					// $detalle[1] -> idPedidos involucrados
					// $detalle[2] -> Cantidad total
					// $detalle[3] -> Cantidad Anillados
					// $detalle[4] -> Cantidad de paginas
					// $detalle[5] -> Sucursal Entrega
					// $detalle[6] -> Sucursal Imprime

                    $detalle = explode(';', $detalle);
                    
                    $idDocumentoPedido = (int) $detalle[0];
                    $pedidos = (strpos($detalle[1],',') !== false ? explode(",", $detalle[1]) : array($detalle[1]));
                    $anillados = (int) $detalle[3];
                    $sucursalEntrega = (int) $detalle[5];
                    $sucursalImprime = (int) $detalle[6];

                    foreach ($pedidos as $idPedido) {
                    	$pedidoDocumento = Pedidodocumento::model()->findByAttributes(
                			array(
                				'idDocumento'=>$idDocumentoPedido, 
                				'idPedido'=>(int) $idPedido,
                				'idEstado'=>1
                			)
                		);

                		if($pedidoDocumento instanceof Pedidodocumento) {
                			$det = new Impresiondetalle();
		                    $det->idImpresion = $model->idImpresion;
		                    $det->idPedido = (int) $idPedido;
		                    $det->idDocumento = $idDocumentoPedido;
		                    $det->nCantidad = (int) ($pedidoDocumento->nCopias - $pedidoDocumento->nImpresos);
		                    $det->idSucursalEntrega = $sucursalEntrega;
		                    $det->idSucursalImpresion = $sucursalImprime;
		                    $det->nAnillados = $pedidoDocumento->nAnillado;
		                    $det->save();
		                    
	                    	$pedidoDocumento->nImpresos = $pedidoDocumento->nCopias;
		                    $pedidoDocumento->idEstado = 2;
		                    $pedidoDocumento->update(array('nImpresos', 'idEstado'));

		                    $pedido = Pedido::model()->with('documentos')->findByPk($pedidoDocumento->idPedido);
		                    $cambiaEstado = true;
		                    foreach ($pedido->documentos as $doc) {
		                    	if($doc->idEstado == 1) {
		                    		$cambiaEstado = false;
		                    		break;
		                    	}
		                    }

		                    if($cambiaEstado) {
		                    	$pedido->idEstado = 3;
		                    	$pedido->update(array('idEstado'));
		                    }

                		}
                    }
                }

				$this->redirect(array('ver','id'=>$model->idImpresion));
			}
		}

		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.dataTables.min.js', CClientScript::POS_END);
		Yii::app()->clientScript->registerScript('table',
        "var oTable;

        $(document).ready(function() {
            oTable = $('#impresion-detalle').dataTable({
                'bLengthChange': false,
                'bFilter': false,
                'sServerMethod': 'POST',
                'bProcessing': true,
                'sAjaxSource': '" . $this->createUrl('impresion/pendientes') . "',
                'oLanguage': {
                    'sUrl': '" . Yii::app()->baseUrl . "/js/es_ES.txt'
                },
                'aoColumnDefs': [
                    { 'bVisible': false, 'aTargets': [ 3 ] }, {'bVisible': false, 'aTargets': [ 0 ] }
                ],
            });
        } );
		
        function serialize(arr, from, to)
        {
            var res = '';
            for(i=from; i<=to; i++) {
                if(i == to)
                    res += arr[i];
                else
                    res += arr[i] + ';';
            }

            return res;
        }

        $(document).on('click','a.detalle',function(){
        	$.post('" . Yii::app()->baseUrl . "/impresion/detalleAjax/ped/' + $(this).attr('data'),function(data){
        		$('.modal-body').html($.parseJSON(data)); 
				$('#idModal').modal();
        	});
        });

		$('.table').on('click', 'input[type=checkbox]', function (){
            if($(this).is(':checked')) {
                $(this).attr('checked','checked');
            } else {
                $(this).removeAttr('checked');
            }
            var resultHtml = $(this)[0].outerHTML;
            var parentHtml = $(this).parent().parent()[0]._DT_RowIndex;
            var numberOfRow = Number($(this).attr('value'));
            oTable.fnUpdate(resultHtml, parentHtml, numberOfRow, false);
        });

		$('.table').on('change', 'select', function (){
			var selected = $(this).val();

			$('option', this).each(function() {

			    if($(this).val() == selected) {
			        $(this).attr('selected', true);
			    } else {
			    	$(this).removeAttr('selected');
			    }
			});

            var resultHtml = this.outerHTML;
            var parentHtml = $(this).parent().parent()[0]._DT_RowIndex;
            var numberOfRow = 8;
            oTable.fnUpdate(resultHtml, parentHtml, numberOfRow, false);
        });

		$('#impresion-form').submit( function(e) {
			e.preventDefault();
            var sData = oTable.fnGetData();

            for ( var i=0 ; i<sData.length ; i++ )
            {
            	if(sData[i][1].indexOf('checked') > -1) {
            		var nHidden = document.createElement( 'input' );
            		nHidden.type = 'hidden';
            		nHidden.name = 'impresion_det[]';
            		
            		sData[i][7] = $(sData[i][7] + ' option:selected').val();
            		sData[i][8] = $(sData[i][8] + ' option:selected').val();

            		sData[i][2] = sData[i][2].replace('<span class=\"coddoc\">','').replace('</span>', '');

            		nHidden.value = serialize(sData[i], 2, 8);
            		this.appendChild( nHidden );
            	}
            }

            this.submit();
            return false;
        } ); ", CClientScript::POS_END);
		
		$this->render('create',array(
			'model'=>$model
		));
	}

	public function actionPendientes()
	{
		$detalles = Pedidodocumento::model()->with('documento', 'pedido')->pendientesImpresion()->findAll();
		$sucursales = CHtml::listData(Sucursal::model()->findAll(), 'idSucursal', 'sNombreSucursal');
		$aDocumentos = array();

		$index = 0;
		foreach ($detalles as $detalle) {
			array_push($aDocumentos,
                array(
                	$index,
                    TbHtml::checkBox("s" . $detalle->documento->idDocumento),
                    "<span class=\"coddoc\">".$detalle->documento->idDocumento."</span>",
                    $detalle->pedidosInvolucrados,
                    $detalle->cantidad,
                    $detalle->anillado,
                    $detalle->documento->nPaginas,
                    TbHtml::dropDownList("se" . $index, $detalle->pedido->idSucursal, $sucursales, array('disabled'=>'disabled')),
                    TbHtml::dropDownList("si" . $index, $detalle->pedido->idSucursal, $sucursales, array('value'=>8)),
                    $detalle->documento->sObservaciones,
                    TbHtml::link(
                    	"ver detalle", 
                    	'#',
                    	array('class'=>'detalle', 'data'=>$detalle->pedidosInvolucrados)
                    ),
                    TbHtml::link(
                    	'<i class="icon-file"></i>', 
                    	$detalle->documento->physicalLocation
                    ),
                )
            );
            $index++;
		}

        echo CJSON::encode(array('aaData'=>$aDocumentos));
	}

	public function actionDetalleAjax()
	{
		$pedidos = $_GET['ped'];

		$criteria=new CDbCriteria;
		$criteria->addCondition("idPedido IN(" . $pedidos . ")");
		$pedidos = Pedido::model()->with('usuario')->findAll($criteria);

		$body = '<p class="text-info">Pedidos en los que aparece este documento</p>';

		$body .= "<div class=\"row-fluid\">";
		foreach ($pedidos as $pedido) {
			$body .= "<p><strong>Pedido C&oacute;digo: </strong>" . $pedido->idPedido . "</p>";
			$body .= "<p><strong>Usuario: </strong>" . (($pedido->usuario == null) ? 'Cliente Mostrador' : $pedido->usuario->fullName) . "</p>";
			$body .= "<p><strong>Fecha: </strong>" . $pedido->dFechaPedido . "</p>";
			$body .= "<hr />";
		}

		echo CJSON::encode($body);
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionVer($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Impresion'])) {
			$model->attributes=$_POST['Impresion'];
			if ($model->save()) {
				$this->redirect(array('ver','id'=>$model->idImpresion));
			}
		}

		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.dataTables.min.js', CClientScript::POS_END);
		Yii::app()->clientScript->registerScript('table',
        "var oTable;

        $(document).ready(function() {
            oTable = $('#impresion-detalle').dataTable({
                'bLengthChange': false,
                'bFilter': false,
                'sServerMethod': 'POST',
                'bProcessing': true,
                'iDisplayLength' : 20,
                'sAjaxSource': '" . $this->createUrl('impresion/detalle', array('id'=>$id)) . "',
                'oLanguage': {
                    'sUrl': '" . Yii::app()->baseUrl . "/js/es_ES.txt'
                },
                'aoColumnDefs': [
                	{ 'bVisible': false, 'aTargets': [ 0 ] },
                	{ 'bVisible': false, 'aTargets': [ 1 ] },
                	{ 'bVisible': false, 'aTargets': [ 9 ] }
                ],
                'fnDrawCallback': function ( oSettings ) {
					if ( oSettings.aiDisplay.length == 0 )
					{
						return;
					}
					
					var nTrs = $('#impresion-detalle tbody tr');
					var iColspan = nTrs[0].getElementsByTagName('td').length;
					var sLastGroup = '';
					for ( var i=0 ; i<nTrs.length ; i++ )
					{
						var iDisplayIndex = oSettings._iDisplayStart + i;
						var sGroup = oSettings.aoData[ oSettings.aiDisplay[iDisplayIndex] ]._aData[1];
						if ( sGroup != sLastGroup )
						{
							var totalSum = 0;
							var sData = oTable.fnGetData();

				            for ( var j=0 ; j<sData.length ; j++ )
				            {
				            	if(sData[j][1] == sGroup) {
				            		totalSum += Number(sData[j][3]);
				            	}
				            }

							var nGroup = document.createElement( 'tr' );
							var nCell = document.createElement( 'td' );
							nCell.colSpan = iColspan;
							nCell.className = 'group';
							nCell.innerHTML = 'Documento: ' + sGroup + ' - Total a imprimir: ' + totalSum;
							nGroup.appendChild( nCell );
							nTrs[i].parentNode.insertBefore( nGroup, nTrs[i] );
							sLastGroup = sGroup;
						}
					}
				},
            });
        } );", CClientScript::POS_END);

		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function actionDetalle($id)
	{
		$detalles = Impresiondetalle::model()->with('sucursalEntrega', 'sucursalImpresion', 'documento')->findAllByAttributes(array('idImpresion'=>(int) $id));
		$aDetalle = array();

		foreach ($detalles as $detalle) {
			array_push($aDetalle,
                array(
                    "",
                    "",
                    $detalle->idDocumento,
                    $detalle->idPedido,
                    $detalle->nCantidad,
                    $detalle->nAnillados,
                    $detalle->documento->nPaginas,
                    $detalle->sucursalEntrega->sNombreSucursal,
                    isset($detalle->sucursalImpresion) ? $detalle->sucursalImpresion->sNombreSucursal : 'No se asignó',
                    $detalle->documento->sObservaciones,
                    "", TbHtml::link(
                    	'<i class="icon-file"></i>', 
                    	$detalle->documento->physicalLocation
                    )));
		}

        echo CJSON::encode(array('aaData'=>$aDetalle));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionEliminar($id)
	{
		if (Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			$model = $this->loadModel($id);
			foreach ($model->impresiondetalles as $detalle) {
				$pedidoDoc = Pedidodocumento::model()->findByAttributes(
					array('idDocumento'=>$detalle->idDocumento, 'idPedido'=>$detalle->idPedido));
				$pedidoDoc->nImpresos -= $detalle->nCantidad;
				$pedidoDoc->idEstado = 1;

				$pedidoDoc->update();
				$detalle->delete();
			}

			$model->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if (!isset($_GET['ajax'])) {
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
			}
		} else {
			throw new CHttpException(400,'Pedido invalido. Porfavor, no repita este pedido nuevamente.');
		}
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Impresion('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['Impresion'])) {
			$model->attributes=$_GET['Impresion'];
		}

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Impresion the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Impresion::model()->findByPk($id);
		if ($model===null) {
			throw new CHttpException(404,'La pagina solicitada no existe.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Impresion $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax']==='impresion-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionDetalleAjaxGrid(){
        if(isset($_GET['id'])) {
        	$idDocumento = (int)(explode(',', $_GET['id']));
        	$idDocumento = $idDocumento[1];

            $model = Pedido::model()
            	->findAllBySql('SELECT * FROM pedido WHERE idPedido IN (SELECT idPedido FROM pedidodocumento WHERE idDocumento = :idDocumento)',
            	array(':idDocumento'=>$idDocumento));

            $aDocumentos = array();

            foreach($model as $pedido) {
            	$cantidadDocumentos = 0;
            	$documentosPedidos = Pedidodocumento::model()->findAllByAttributes(array('idDocumento'=>$idDocumento, 'idPedido'=>$pedido->idPedido));

            	foreach ($documentosPedidos as $doc) {
            		$cantidadDocumentos += $doc->nCopias;
            	}

                array_push($aDocumentos,
                    array(
                        'id' => $pedido->idPedido,
                        'copias' => $cantidadDocumentos,
                        'fecha' => $pedido->dFechaPedido,
                        'cliente' => isset($pedido->usuario) ? $pedido->usuario->fullName : 'Cliente Mostrador',
                        'estado' => $pedido->estado->sDescripcion,
                        'sucursal' => $pedido->sucursal->sNombreSucursal
                    )
                );
            }

            $this->renderPartial('_pedidosDetalles', new CArrayDataProvider($aDocumentos), false, true);
        }
    }
}