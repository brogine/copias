<?php

class CarritoController extends Controller {

    public function filters()
    {
        return array(
            'accessControl',
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
                'actions'=>array('notificacionesDM', 'realizado', 'error', 'pendiente'),
                'users'=>array('*'),
            ),
            array('allow',
                'actions'=>array('index', 'agregar', 'quitar', 'cambioAnillados', 'cambioSucursal'),
                'users'=>array('@'),
            ),
            array('deny',
                'users'=>array('*'),
            ),
        );
    }

    public function actionNotificacionesDM()
    {
        if(isset($_POST) && isset($_POST['Notificacion'])) {
            $notificacion = $_POST["Notificacion"];
            $notificacion = str_replace("\'",'"',$notificacion);
            $notificacion = simplexml_load_string($notificacion);

            Yii::log(CVarDumper::dumpAsString($notificacion), 'info', 'pagos.NotificacionDineroMail');

            foreach($notificacion->operaciones->operacion as $operacion) {
                $pedidoPago = Pago::model()->findByPk((int) $operacion->id);
                if($pedidoPago instanceof Pago && $pedidoPago->bFinalizado == 0) {
                    $pedidoPago->fFechaCancelacion = new CDbExpression('NOW()');
                    $pedidoPago->bFinalizado = 1;
                    $pedidoPago->save();

                    $pedido = Pedido::model()->findByAttributes(array('idPago'=>$pedidoPago->idPago));
                    $pedido->idEstado = 2;
                    $pedido->bCompletado = 1;
                    $pedido->update(array('idEstado', 'bCompletado'));
                }
            }

            unset($notificacion);

            Yii::app()->end();
        }
    }

    public function actionIndex() {
        $valorAnillado = Configuracion::PrecioAnillado();

        if(isset($_POST) && isset($_POST['idSucursal']) && Yii::app()->shoppingCart->getItemsCount() > 0) {

            if(empty($_POST['idSucursal'])) {
                Yii::app()->user->setFlash('warning', '<strong>Advertencia</strong><br>Debes seleccionar una sucursal para retirar tu pedido.');
            } else {

                $formaPago = 0;
                $completado = 0;
                if(isset($_POST['retiro'])) {
                    $formaPago = 2;
                    $completado = 1;
                } else if (isset($_POST['dineromail'])) {
                    $formaPago = 1;
                    $completado = 0;
                }

                if($formaPago > 0) {
                    $model = new Pedido();
                    $model->dFechaPedido = new CDbExpression('NOW()');
                    $model->idUsuario = Yii::app()->user->getId();
                    $model->idSucursal = (int) $_POST['idSucursal'];
                    $model->idEstado = 1;
                    $model->bCompletado = $completado;

                    $pedidoPago = new Pago();
                    $pedidoPago->idFormaPago = $formaPago;
                    $pedidoPago->fFechaCreacion = new CDbExpression('NOW()');

                    if($model->save()) {
                        foreach(Yii::app()->shoppingCart as $key => $item) {

                            if($item instanceof Documento) {
                                $det = new Pedidodocumento();
                                $det->idPedido = $model->idPedido;
                                $det->idDocumento = $item->idDocumento;
                                
                                $det->nCopias = $item->getQuantity();
                                $det->nAnillado = $item->anilladosTmp;

                                $det->nValorAnillados = $det->nAnillado * $valorAnillado;

                                $det->nValorUnitario = $item->price;
                                $det->nValorNeto = $item->price * $det->nCopias;

                                $det->save();
                            } elseif ($item instanceof Articulo) {
                                $det = new Pedidoarticulo();
                                $det->idPedido = $model->idPedido;
                                $det->idArticulo = $item->idArticulo;
                                $det->nCantidad = $item->getQuantity();

                                $det->nValorUnitario = $item->nPrecio;
                                $det->nValorNeto = $item->nPrecio * $det->nCantidad;
                                $det->save();
                                
                                $item->nStock = $item->nStock - $det->nCantidad;
                                $item->update(array('nStock'));
                            }
                        }

                        $total = Yii::app()->shoppingCart->getCost();
                        $pedidoPago->nTotal = $total;
                        $pedidoPago->save();

                        $model->idPago = $pedidoPago->idPago;
                        $model->save();

                        Yii::app()->shoppingCart->clean();
                        Yii::app()->shoppingCart->clear();

                        if($formaPago == 1) {
                            $this->render('pago', array('pedido'=>$model->idPedido, 'pago'=>$pedidoPago->idPago, 'total'=>$total));
                            Yii::app()->end();
                        } else {
                            Yii::app()->user->setFlash('success', '<strong>Pedido a confirmar Nº ' . $model->idPedido .'</strong><br>Con este número acercate a alguna de nuestras sucursales. <br>Esperamos tu pago para empezar a imprimir tu pedido.<br><strong>¡NO TE OLVIDES!</strong>');
                        }
                        
                    }
                }

            }
        }

        Yii::app()->clientScript->registerScript('table', 
        "$('#pedido-detalle').on('keyup', '.anill', function (){
            var max = Number($(this).parent().parent().children()[2].innerHTML);
            if($( this ).val() > max) {
                $(this).val(max);
            }

            updateTotals();
            
            $(this).attr('value', $(this).val());
            var id = 'd' + $(this).parent().parent().children()[0].childNodes[0].nodeValue;
            $.post( '" . $this->createUrl('carrito/cambioAnillados') . "', { id: id, anill: $(this).val() } );
        });
        function updateTotals() {
            var total = 0;
            $( '#pedido-detalle').find('tbody tr').each(function() {
                var cant = Number($(this).children('.cant')[0].childNodes[0].nodeValue);
                var unit = parseFloat($(this).children('.importe')[0].childNodes[0].nodeValue.replace('$','')).toFixed(2);
                var cantanill = Number($(this).children('.ianill').children('.anill').val());
                var valanill = parseFloat($('#vAnillado')[0].childNodes[0].nodeValue).toFixed(2);
                var calc = (cant * unit) + (cantanill * valanill);
                calc = +parseFloat(calc).toFixed(2);

                total += calc;
                $(this).children('.subtotal').text('$' + calc);
            });
            total = +parseFloat(total).toFixed(2);
            $('#total').text(total);
        }
        $( '#idSucursal' ).change(function() {
            $.post( '" . $this->createUrl('carrito/cambioSucursal') . "', { sucursal: $(this).val() });
        });
        $(window).keydown(function(event){
            if(event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });",CClientScript::POS_END);

        $sucursales = CHtml::listData(Sucursal::model()->findAll(), 'idSucursal', 'fullName');

        $this->render('pedido', array('sucursales'=>$sucursales, 'valorAnillado'=>$valorAnillado));
    }

    public function actionAgregar() {
        if(isset($_POST) && isset($_POST['quant']) && isset($_POST['id']) && isset($_POST['type']) ) {
            $cant = CPropertyValue::ensureInteger($_POST['quant']);
            $id = CPropertyValue::ensureInteger($_POST['id']);

            switch($_POST['type']) {
                case 'doc':
                    $doc = Documento::model()->findByPk($id);
                    Yii::app()->shoppingCart->put($doc, $cant);
                    break;
                case 'art':
                    $art = Articulo::model()->findByPk($id);
                    Yii::app()->shoppingCart->put($art, $cant);
                    break;
            }

            echo "$" . Yii::app()->shoppingCart->getCost();
        }
    }

    public function actionQuitar() {
        if(isset($_POST) && isset($_POST['id']) && isset($_POST['type'])) {
            if(isset($_POST['quant']))
                $cant = CPropertyValue::ensureInteger($_POST['quant']);
            else
                $cant = true;
            $id = CPropertyValue::ensureInteger($_POST['id']);

            switch($_POST['type']) {
                case 'doc':
                    $doc = Documento::model()->findByPk($id);
                    if($cant == true)
                        Yii::app()->shoppingCart->remove($doc->getId());
                    else
                        Yii::app()->shoppingCart->update($doc, $cant);
                    break;
                case 'art':
                    $art = Articulo::model()->findByPk($id);
                    if($cant == true)
                        Yii::app()->shoppingCart->remove($art->getId());
                    else
                        Yii::app()->shoppingCart->update($art, $cant);
                    break;
            }
        }
    }

    public function actionCambioAnillados() {
        if(isset($_POST) && isset($_POST['id']) && isset($_POST['anill'])) {
            $element = Yii::app()->shoppingCart[$_POST['id']];
            $element->anilladosTmp = ((int) $_POST['anill']) > $element->quantity ? $element->quantity : (int) $_POST['anill'];
            Yii::app()->shoppingCart->update($element, $element->quantity);
        }
    }

    public function actionCambioSucursal() {
        if(isset($_POST) && isset($_POST['sucursal'])) {
            Yii::app()->shoppingCart->sucursal = (int) $_POST['sucursal'];
        }
    }

    public function actionRealizado($id) {
        Yii::app()->user->setFlash('success', 
            '<strong>Pedido confirmado Nº ' . $id . '.</strong><br>Con este número podés pasar a retirar tu pedido por la sucursal seleccionada luego de 48h.');
        
        $model = Pedido::model()->findByPk($id);
        $model->bCompletado = 1;
        $model->update(array('bCompletado'));
        
        $this->render('pedido');
    }

    public function actionError($id) {
        Yii::app()->user->setFlash('error', '<strong>No se realizó su pedido</strong>');
        
        $model = Pedido::model()->findByPk($id);
        $model->bCompletado = 1;
        $model->update(array('bCompletado'));
        
        $this->render('pedido');
    }

    public function actionPendiente($id) {
        Yii::app()->user->setFlash('info', '<strong>Pedido pendiente Nº ' . $id . '.</strong><br>Tu pago se encuentra pendiente. Apenas recibamos la confirmación del mismo, vas a poder pasar a retirar tu pedido.');
        
        $model = Pedido::model()->findByPk($id);
        $model->bCompletado = 1;
        $model->update(array('bCompletado'));
        
        $this->render('pedido');
    }
}