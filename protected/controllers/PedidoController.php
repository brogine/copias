<?php

date_default_timezone_set('America/Argentina/Buenos_Aires');
class PedidoController extends Controller
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
                'expression'=>'Yii::app()->user->hasAccessTo(Permiso::MODULO_PEDIDO)',
            ),
            array('deny',
                'users'=>array('*'),
            ),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionVer($id, $renderComp = false)
	{
        if($renderComp) {
            Yii::app()->clientScript->registerScript('loadComp',
                'document.getElementById("pdfToPrint").focus();document.getElementById("pdfToPrint").contentWindow.print();'
            );
        }

        Yii::app()->clientScript->registerScript('printComp',
            "$('#print').click(function(e) {
                e.preventDefault();

                var params = this.getPrintParams();
                params.interactive = params.constants.interactionLevel.silent;

                document.getElementById('pdfToPrint').print(params);

            });"
        );

		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionNuevo()
	{
		$model=new Pedido;
        $documentos = new Documento('search');
        $articulos = new Articulo('search');

        if(isset($_GET['ajax'])) { // Filter
            if($_GET['ajax'] == 'gvDocumentos') {
                $documentos->unsetAttributes();
                $documentos->keyword = isset($_GET['docFilter']) ? $_GET['docFilter'] : '';
                $documentos->filtroInstituciones = isset($_GET['filtroInstituciones']) ? $_GET['filtroInstituciones'] : array();
                $documentos->filtroProfesores = isset($_GET['filtroProfesores']) ? $_GET['filtroProfesores'] : array();
                $documentos->filtroMaterias = isset($_GET['filtroMaterias']) ? $_GET['filtroMaterias'] : array();
                $documentos->filtroCarreras = isset($_GET['filtroCarreras']) ? $_GET['filtroCarreras'] : array();
                $documentos->filtroCursos = isset($_GET['filtroCursos']) ? $_GET['filtroCursos'] : array();
            }

            if($_GET['ajax'] == 'gvArticulos') {
                $articulos->unsetAttributes();
                $articulos->keyword = isset($_GET['artFilter']) ? $_GET['artFilter'] : '';
            }
        }

        $valorAnillado = Configuracion::PrecioAnillado();

		if (isset($_POST['Pedido'])) {
			$model->attributes=$_POST['Pedido'];
            $model->dFechaPedido = new CDbExpression('NOW()');
            $model->dFechaEntrega = $model->dFechaEntrega != '' ? date("Y-m-d", strtotime($model->dFechaEntrega)) : null;
            $model->idUsuarioAlta = Yii::app()->user->getId();
            $model->nFaltante = -1;

            if(!isset($_POST['pedido_det']))
                Yii::app()->user->setFlash('error', 'El pedido debe tener algún documento o artículo.');
            else
            {
                if ($model->save()) {

                    $todosEntregados = true;
                    $forceImpreso = -1;
                    $forceEntregado = -1;
                    $totalFinal = 0;
                    switch ($model->idEstado) {
                        case 1:
                        case 2:
                            $forceEntregado = 0;
                            $forceImpreso = 1;
                            break;
                        case 3:
                        case 4:
                            $forceEntregado = 0;
                            $forceImpreso = 2;
                            break;
                        case 5:
                            $forceEntregado = 1;
                            $forceImpreso = 2;
                            break;
                    }

                    foreach ($_POST['pedido_det'] as $detalle) {

                        $detalle = explode(';', $detalle);
                        $detalle[2] = preg_replace("/[^0-9]/","",$detalle[2]);

                        if($detalle[1] == "doc") {
                            $det = new Pedidodocumento();
                            $det->idPedido = $model->idPedido;
                            $det->idDocumento = (int) $detalle[2];
                            $documentoTmp = Documento::model()->findByPk($det->idDocumento);
                            $det->nCopias = (int) $detalle[4];
                            $det->nAnillado = (int) $detalle[7];

                            if($forceImpreso != -1 && $forceEntregado != -1) {
                                $det->ChangeEntregado($forceEntregado);
                                $det->ChangeEstado($forceImpreso);
                            }
                            if(!$det->ChangeEntregado(($detalle[8] == "Si") ? 1 : 0)) {
                                $todosEntregados = false;
                            }

                            $det->nValorAnillados = $det->nAnillado * $valorAnillado;
                            $det->nValorUnitario = $documentoTmp->price;
                            $det->nValorNeto = $documentoTmp->price * $det->nCopias;
                            $totalFinal += $det->nValorNeto + $det->nValorAnillados;

                            $det->save();

                        } elseif($detalle[1] == "art") {
                            $det = new Pedidoarticulo();
                            $det->idPedido = $model->idPedido;
                            $det->idArticulo = (int) strip_tags(str_replace('a', '', $detalle[2]));
                            $det->nCantidad = (int) $detalle[4];

                            $det->bEntregado = ($detalle[8] == "Si") ? 1 : 0;
                            if($det->bEntregado == 0){
                                $todosEntregados = false;
                            }

                            $artTmp = Articulo::model()->findByPk($det->idArticulo);

                            $det->nValorUnitario = $artTmp->nPrecio;
                            $det->nValorNeto = $artTmp->nPrecio * $det->nCantidad;
                            $totalFinal += $det->nValorNeto;
                            $det->save();
                            
                            $artTmp->nStock = $artTmp->nStock - $det->nCantidad;
                            $artTmp->update(array('nStock'));
                        }
                    }

                    if($todosEntregados) {
                        $model->idEstado = 5;
                        $model->update(array('idEstado'));
                    }

                    $this->redirect(array('ver','id'=>$model->idPedido, 'renderComp'=>true));
                }
            }
		}

        $aaData = '';
        if(isset($_POST['pedido_det']) && count($_POST['pedido_det']) > 0) {
            $aaData .= 'aaData: [';
            foreach ($_POST['pedido_det'] as $detalle) {
                $detalle = explode(';', $detalle);
                $detalle[2] = preg_replace("/[^0-9]/","",$detalle[2]);
                
                $aaData .= '['.$detalle[0].',"'.$detalle[1].'",'.$detalle[2].',"'.$detalle[3].'",'.$detalle[4].',"'.$detalle[5].'","'.$detalle[6].'",';
                $aaData .= ($detalle[1] == "art" ? 0 : "'<input type=\"text\" name=\"anill\" class=\"anill\" value=\"0\">'").',';
                $aaData .= "'<input type=\"checkbox\" value=\"8\" name=\"ent\" id=\"ent\" " . (($detalle[8] == "No") ? "" : "checked") . ">',";
                $aaData .= "'<a href=\"\" data-original-title=\"Quitar\" class=\"delete\"><img src=\"".Yii::app()-> baseUrl."/images/remove.png\" alt=\"Quitar\"/></a>'";
                $aaData .= '],';
            }
            $aaData .= '],';
        }

		$usuarios = CHtml::listData(Usuario::model()->findAll(), 'idUsuario', 'fullNameDni');
		$estados = CHtml::listData(Pedidoestado::model()->findAll(), 'idPedidoEstado', 'sDescripcion');
		$sucursales = CHtml::listData(Sucursal::model()->findAll(), 'idSucursal', 'sNombreSucursal');
        $instituciones = CHtml::listData(Institucion::model()->findAll(), 'idInstitucion', 'sDescripcion');
        $materias = CHtml::listData(Materia::model()->findAll(), 'idMateria', 'sDescripcion');
        $profesores = CHtml::listData(Profesor::model()->findAll(), 'idProfesor', 'FullName');
        $carreras = CHtml::listData(Carrera::model()->findAll(), 'idCarrera', 'sDescripcion');
        $cursos = CHtml::listData(Curso::model()->findAll(), 'idCurso', 'sDescripcion');
        Yii::app()->clientScript
        ->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.dataTables.min.js')
        ->registerScript('table',
        'var giCount = 1;var oTable;var nAnillados = 0;
        function serialize(e,t){var n="";for(i=0;i<=t;i++){if(typeof e[i]=="string"||e[i]instanceof String){if(e[i].indexOf("checkbox")>-1){e[i]=e[i].indexOf("checked")>-1?"Si":"No"}else if(e[i].indexOf(\'type="text"\')>-1){e[i]=$(e[i]).val()}else if(e[i].indexOf("<span")>-1){e[i]=e[i].replace(\'<span class="anill">\',"").replace("</span>","")}if(e[i].length>50){e[i]=e[i].substr(0,50)}}if(i==t)n+=e[i];else n+=e[i]+";"}return n}
        function tableDataChanged(){var e=parseFloat($("#totalFooterFinal").html().replace("$",""));var t=$("#Pedido_nCobrado").val();t=t!=""?parseFloat(t):0;if(t>e){t=e;$("#Pedido_nCobrado").val(e)}var n=(e-t).toFixed(2);$("#restante").val("$"+n)}
        function fnRemoveRow(e){if(e.children[5].innerHTML!="0"){nAnillados=nAnillados-Number(e.children[5].children[0].value)}oTable.fnDeleteRow(e._DT_RowIndex);tableDataChanged()}
        function fnClickAddRow(e,t,n,r,i){var s=$("#pedido-detalle").dataTable().fnAddData([giCount,e,t,n,r,"$"+parseFloat(i.replace(",","")).toFixed(2),"$"+parseFloat(r*parseFloat(i.replace(",",""))).toFixed(2),e=="art"?0:\'<input type="text" name="anill" class="anill" value="0">\',\'<input type="checkbox" value="8" name="ent" id="ent">\',\'<a href="javascript:void(0)" data-original-title="Quitar" class="delete"><img src="' . Yii::app()->baseUrl . '/images/remove.png" class="delete" alt="Quitar"/></a>\']);giCount++;tableDataChanged()}
        $("#pedido-detalle").on("click",".delete",function(e){ e.preventDefault(); fnRemoveRow($(this).parent().parent().parent()[0]); return false; });
        $("#pedido-form").submit(function(){var e=oTable.fnGetData();for(var t=0;t<e.length;t++){var n=document.createElement("input");n.type="hidden";n.name="pedido_det[]";n.value=serialize(e[t],8);this.appendChild(n)}this.submit();return false})
        $("#Pedido_nCobrado").on("change keyup",function(){tableDataChanged();var e=parseFloat($("#totalFooterFinal").html().replace("$",""));var t=$("#Pedido_nCobrado").val();t=t!=""?parseFloat(t):0;var n=e*3/10;if(t>n){$("#Pedido_idEstado option[value=2]").removeAttr("disabled")}else{$("#Pedido_idEstado option[value=2]").attr("disabled","disabled")}})
        $(".table").on("click","input[type=checkbox]",function(){if($(this).is(":checked")){$(this).attr("checked","checked")}else{$(this).removeAttr("checked")}var e=$(this)[0].outerHTML;var t=$(this).parent().parent()[0]._DT_RowIndex;var n=Number($(this).attr("value"));oTable.fnUpdate(e,t,n)})
        $("#pedido-detalle").on("keyup",".anill",function(){var e=Number($(this).parent().parent().children()[2].innerHTML);if($(this).val()>e){$(this).val(e)}nAnillados=0;$("#pedido-detalle").find(".anill").each(function(){var e=$(this).is("span")?$(this).text():$(this).val();nAnillados+=Number(e)});$(this).attr("value",$(this).val());oTable.fnUpdate($(this)[0].outerHTML,$(this).parent().parent()[0]._DT_RowIndex,7)})
        $.fn.dataTableExt.oApi.fnPagingInfo=function(e){return{iStart:e._iDisplayStart,iEnd:e.fnDisplayEnd(),iLength:e._iDisplayLength,iTotal:e.fnRecordsTotal(),iFilteredTotal:e.fnRecordsDisplay(),iPage:e._iDisplayLength===-1?0:Math.ceil(e._iDisplayStart/e._iDisplayLength),iTotalPages:e._iDisplayLength===-1?0:Math.ceil(e.fnRecordsDisplay()/e._iDisplayLength)}}
        $.extend($.fn.dataTableExt.oPagination,{bootstrap:{fnInit:function(e,t,n){var r=e.oLanguage.oPaginate;var i=function(t){t.preventDefault();if(e.oApi._fnPageChange(e,t.data.action)){n(e)}};$(t).addClass("pagination").append("<ul>"+"<li class=\'prev disabled\'><a href=\'#\'>&larr; "+r.sPrevious+"</a></li>"+"<li class=\'next disabled\'><a href=\'#\'>"+r.sNext+" &rarr; </a></li>"+"</ul>");var s=$("a",t);$(s[0]).bind("click.DT",{action:"previous"},i);$(s[1]).bind("click.DT",{action:"next"},i)},fnUpdate:function(e,t){var n=5;var r=e.oInstance.fnPagingInfo();var i=e.aanFeatures.p;var s,o,u,a,f,l=Math.floor(n/2);if(r.iTotalPages<n){a=1;f=r.iTotalPages}else if(r.iPage<=l){a=1;f=n}else if(r.iPage>=r.iTotalPages-l){a=r.iTotalPages-n+1;f=r.iTotalPages}else{a=r.iPage-l+1;f=a+n-1}for(s=0,iLen=i.length;s<iLen;s++){$("li:gt(0)",i[s]).filter(":not(:last)").remove();for(o=a;o<=f;o++){u=o==r.iPage+1?"class=\'active\'":"";$("<li "+u+"><a href=\'#\'>"+o+"</a></li>").insertBefore($("li:last",i[s])[0]).bind("click",function(n){n.preventDefault();e._iDisplayStart=(parseInt($("a",this).text(),10)-1)*r.iLength;t(e)})}if(r.iPage===0){$("li:first",i[s]).addClass("disabled")}else{$("li:first",i[s]).removeClass("disabled")}if(r.iPage===r.iTotalPages-1||r.iTotalPages===0){$("li:last",i[s]).addClass("disabled")}else{$("li:last",i[s]).removeClass("disabled")}}}}})
        $(document).ready(function(){oTable=$("#pedido-detalle").dataTable({bLengthChange:false,bFilter:false,sServerMethod:"POST",sPaginationType:"bootstrap",oLanguage:{sUrl:"' . Yii::app()->baseUrl . '/js/es_ES.txt"},aoColumnDefs:[{bVisible:false,aTargets:[0]},{bVisible:false,aTargets:[1]}],' . $aaData . 'fnFooterCallback:function(e,t,n,r,i){var s=0;for(var o=0;o<t.length;o++){s+=t[o][4]*Number(t[o][5].replace("$",""))}var u=e.getElementsByTagName("th");var a=$("#valorAnillado").val()*nAnillados;var f=parseFloat(s+a).toFixed(2);var l=$("#Pedido_nCobrado").val();l=l!=""?parseFloat(l).toFixed(2):0;u[1].innerHTML="$"+parseFloat(a).toFixed(2);u[3].innerHTML="$"+f;$("#restante").val("$"+(f-l))}})})
      ',CClientScript::POS_END);

		$this->render('create',array(
			'model'=>$model
            , 'usuarios' => $usuarios
            , 'estados' => $estados
            , 'sucursales' => $sucursales
            , 'documentos' => $documentos->searchTextLive()
            , 'articulos' => $articulos->searchTextLive()
            , 'valorAnillado' => $valorAnillado
            , 'materias' => $materias
            , 'profesores' => $profesores
            , 'carreras' => $carreras
            , 'cursos' => $cursos
            , 'instituciones' => $instituciones
            , 'filtroProfesores' => $documentos->filtroProfesores
            , 'filtroMaterias' => $documentos->filtroMaterias
            , 'filtroCarreras' => $documentos->filtroCarreras
            , 'filtroCursos' => $documentos->filtroCursos
            , 'filtroInstituciones' => $documentos->filtroInstituciones
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionEditar($id)
	{
    if(isset($id))
		  $model=$this->loadModel($id);
        $documentos = new Documento('search');
        $articulos = new Articulo('search');

        // Busqueda?
        if(isset($_GET['ajax'])) {
            if($_GET['ajax'] == 'gvDocumentos') {
                $documentos->unsetAttributes();
                $documentos->keyword = isset($_GET['docFilter']) ? $_GET['docFilter'] : '';
                $documentos->filtroInstituciones = isset($_GET['filtroInstituciones']) ? $_GET['filtroInstituciones'] : array();
                $documentos->filtroProfesores = isset($_GET['filtroProfesores']) ? $_GET['filtroProfesores'] : array();
                $documentos->filtroMaterias = isset($_GET['filtroMaterias']) ? $_GET['filtroMaterias'] : array();
                $documentos->filtroCarreras = isset($_GET['filtroCarreras']) ? $_GET['filtroCarreras'] : array();
                $documentos->filtroCursos = isset($_GET['filtroCursos']) ? $_GET['filtroCursos'] : array();
            }

            if($_GET['ajax'] == 'gvArticulos') {
                $articulos->unsetAttributes();
                $articulos->keyword = isset($_GET['artFilter']) ? $_GET['artFilter'] : '';
            }
        }

        $valorAnillado = Configuracion::PrecioAnillado();

        // Se submiteó el form?
		if (isset($_POST['Pedido'])) {
            $estadoAnterior = $model->idEstado;
			$model->attributes=$_POST['Pedido'];
            $model->dFechaEntrega = $model->dFechaEntrega != '' ? date("Y-m-d", strtotime($model->dFechaEntrega)) : null;
            
            if(!isset($_POST['pedido_det']))
                Yii::app()->user->setFlash('error', 'El pedido debe tener algún documento o artículo.');
            else
            {
                $model->nFaltante = -1;
    			if ($model->save()) {

                    $todosEntregados = true;
                    $forceImpreso = -1;
                    $forceEntregado = -1;
                    switch ($model->idEstado) {
                        case 1:
                        case 2:
                            $forceEntregado = 0;
                            $forceImpreso = 1;
                            break;
                        case 3:
                        case 4:
                            $forceEntregado = 0;
                            $forceImpreso = 2;
                            break;
                        case 5:
                            $forceEntregado = 1;
                            $forceImpreso = 2;
                            break;
                    }

                    foreach ($_POST['pedido_det'] as $detalle) {
                        $detalle = explode(';', $detalle);

                        if($detalle[1] == "doc") {
                            $det = Pedidodocumento::model()->with('documento')->findByAttributes(
                                        array('idPedido'=>$model->idPedido, 'idDocumento'=>(int) $detalle[2]));
                            if($det instanceof Pedidodocumento) {
                                $det->nCopias = (int) $detalle[4];
                                $det->nAnillado = (int) $detalle[7];

                                if($forceImpreso != -1 && $forceEntregado != -1) {
                                    $det->ChangeEntregado($forceEntregado);
                                    $det->ChangeEstado($forceImpreso);
                                }
                                if(!$det->ChangeEntregado(($detalle[8] == "Si") ? 1 : 0)) {
                                    $todosEntregados = false;
                                }

                                $det->nValorAnillados = $det->nAnillado * $valorAnillado;
                                $det->nValorUnitario = $det->documento->price;
                                $det->nValorNeto = $det->documento->price * $det->nCopias;
                                
                                $det->save();
                            }

                        } elseif($detalle[1] == "art") {
                            $idArticuloTmp = (int) strip_tags(str_replace('a', '', $detalle[2]));

                            $det = Pedidoarticulo::model()->with('articulo')->findByAttributes(
                                        array('t.idPedido'=>$model->idPedido, 't.idArticulo'=>$idArticuloTmp));
                            if($det instanceof Pedidoarticulo) {
                                $det->nCantidad = (int) $detalle[4];
                                if($det->bEntregado == 0) {
                                    $det->bEntregado = ($detalle[8] == "Si") ? 1 : 0;
                                    if($det->bEntregado == 0){
                                        $todosEntregados = false;
                                    }
                                }

                                $det->nValorUnitario = $det->articulo->nPrecio;
                                $det->nValorNeto = $det->articulo->nPrecio * $det->nCantidad;
                                $det->save();
                            }
                        }
                    }

                    if($todosEntregados) {
                        $model->idEstado = 5;
                        $model->save();
                    }

    				$this->redirect(array('ver','id'=>$model->idPedido));
    			}
            }
		}

        $model->dFechaPedido = date("d-m-Y H:i:s",strtotime($model->dFechaPedido));
        $totalAnilladosPedido=Yii::app()->db->createCommand('SELECT SUM(nAnillado) AS cantidad FROM pedidodocumento WHERE idPedido = ' . $model->idPedido)->queryScalar();

        $usuarios = CHtml::listData(Usuario::model()->findAll(), 'idUsuario', 'fullNameDni');
        $estados = CHtml::listData(Pedidoestado::model()->findAll(), 'idPedidoEstado', 'sDescripcion');
        $sucursales = CHtml::listData(Sucursal::model()->findAll(), 'idSucursal', 'sNombreSucursal');
        $instituciones = CHtml::listData(Institucion::model()->findAll(), 'idInstitucion', 'sDescripcion');
        $materias = CHtml::listData(Materia::model()->findAll(), 'idMateria', 'sDescripcion');
        $profesores = CHtml::listData(Profesor::model()->findAll(), 'idProfesor', 'FullName');
        $carreras = CHtml::listData(Carrera::model()->findAll(), 'idCarrera', 'sDescripcion');
        $cursos = CHtml::listData(Curso::model()->findAll(), 'idCurso', 'sDescripcion');

        Yii::app()->clientScript
          ->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.dataTables.min.js')
          ->registerScript('table',
        'var giCount = 1;var oTable;var nAnillados = ' . (isset($totalAnilladosPedido) ? $totalAnilladosPedido : 0) . ';
        function serialize(e,t){var n="";for(i=0;i<=t;i++){if(typeof e[i]=="string"||e[i]instanceof String){if(e[i].indexOf("checkbox")>-1){e[i]=e[i].indexOf("checked")>-1?"Si":"No"}else if(e[i].indexOf(\'type="text"\')>-1){e[i]=$(e[i]).val()}else if(e[i].indexOf("<span")>-1){e[i]=e[i].replace(\'<span class="anill">\',"").replace("</span>","")}if(e[i].length>50){e[i]=e[i].substr(0,50)}}if(i==t)n+=e[i];else n+=e[i]+";"}return n}
        function tableDataChanged(){var e=parseFloat($("#totalFooterFinal").html().replace("$",""));var t=$("#Pedido_nCobrado").val();t=t!=""?parseFloat(t):0;if(t>e){t=e;$("#Pedido_nCobrado").val(e)}var n=(e-t).toFixed(2);$("#restante").val("$"+n)}
        function fnRemoveRow(e){if(e.children[5].innerHTML!="0"){nAnillados=nAnillados-Number(e.children[5].children[0].value)}oTable.fnDeleteRow(e._DT_RowIndex);tableDataChanged()}
        function fnClickAddRow(e,t,n,r,i){var s=$("#pedido-detalle").dataTable().fnAddData([giCount,e,t,n,r,"$"+parseFloat(i.replace(",","")).toFixed(2),"$"+parseFloat(r*parseFloat(i.replace(",",""))).toFixed(2),e=="art"?0:\'<input type="text" name="anill" class="anill" value="0">\',\'<input type="checkbox" value="8" name="ent" id="ent">\',\'<a href="javascript:void(0)" data-original-title="Quitar" class="delete"><img src="' . Yii::app()->baseUrl . '/images/remove.png" class="delete" alt="Quitar"/></a>\']);giCount++;tableDataChanged()}
        $("#pedido-detalle").on("click",".delete",function(e){ e.preventDefault(); fnRemoveRow($(this).parent().parent().parent()[0]); return false; });
        $("#pedido-form").submit(function(){var e=oTable.fnGetData();for(var t=0;t<e.length;t++){var n=document.createElement("input");n.type="hidden";n.name="pedido_det[]";n.value=serialize(e[t],8);this.appendChild(n)}this.submit();return false})
        $("#Pedido_nCobrado").on("change keyup",function(){tableDataChanged();var e=parseFloat($("#totalFooterFinal").html().replace("$",""));var t=$("#Pedido_nCobrado").val();t=t!=""?parseFloat(t):0;var n=e*3/10;if(t>n){$("#Pedido_idEstado option[value=2]").removeAttr("disabled")}else{$("#Pedido_idEstado option[value=2]").attr("disabled","disabled")}})
        $(".table").on("click","input[type=checkbox]",function(){if($(this).is(":checked")){$(this).attr("checked","checked")}else{$(this).removeAttr("checked")}var e=$(this)[0].outerHTML;var t=$(this).parent().parent()[0]._DT_RowIndex;var n=Number($(this).attr("value"));oTable.fnUpdate(e,t,n)})
        $("#pedido-detalle").on("keyup",".anill",function(){var e=Number($(this).parent().parent().children()[2].innerHTML);if($(this).val()>e){$(this).val(e)}nAnillados=0;$("#pedido-detalle").find(".anill").each(function(){var e=$(this).is("span")?$(this).text():$(this).val();nAnillados+=Number(e)});$(this).attr("value",$(this).val());oTable.fnUpdate($(this)[0].outerHTML,$(this).parent().parent()[0]._DT_RowIndex,7)})
        $.fn.dataTableExt.oApi.fnPagingInfo=function(e){return{iStart:e._iDisplayStart,iEnd:e.fnDisplayEnd(),iLength:e._iDisplayLength,iTotal:e.fnRecordsTotal(),iFilteredTotal:e.fnRecordsDisplay(),iPage:e._iDisplayLength===-1?0:Math.ceil(e._iDisplayStart/e._iDisplayLength),iTotalPages:e._iDisplayLength===-1?0:Math.ceil(e.fnRecordsDisplay()/e._iDisplayLength)}}
        $.extend($.fn.dataTableExt.oPagination,{bootstrap:{fnInit:function(e,t,n){var r=e.oLanguage.oPaginate;var i=function(t){t.preventDefault();if(e.oApi._fnPageChange(e,t.data.action)){n(e)}};$(t).addClass("pagination").append("<ul>"+"<li class=\'prev disabled\'><a href=\'#\'>&larr; "+r.sPrevious+"</a></li>"+"<li class=\'next disabled\'><a href=\'#\'>"+r.sNext+" &rarr; </a></li>"+"</ul>");var s=$("a",t);$(s[0]).bind("click.DT",{action:"previous"},i);$(s[1]).bind("click.DT",{action:"next"},i)},fnUpdate:function(e,t){var n=5;var r=e.oInstance.fnPagingInfo();var i=e.aanFeatures.p;var s,o,u,a,f,l=Math.floor(n/2);if(r.iTotalPages<n){a=1;f=r.iTotalPages}else if(r.iPage<=l){a=1;f=n}else if(r.iPage>=r.iTotalPages-l){a=r.iTotalPages-n+1;f=r.iTotalPages}else{a=r.iPage-l+1;f=a+n-1}for(s=0,iLen=i.length;s<iLen;s++){$("li:gt(0)",i[s]).filter(":not(:last)").remove();for(o=a;o<=f;o++){u=o==r.iPage+1?"class=\'active\'":"";$("<li "+u+"><a href=\'#\'>"+o+"</a></li>").insertBefore($("li:last",i[s])[0]).bind("click",function(n){n.preventDefault();e._iDisplayStart=(parseInt($("a",this).text(),10)-1)*r.iLength;t(e)})}if(r.iPage===0){$("li:first",i[s]).addClass("disabled")}else{$("li:first",i[s]).removeClass("disabled")}if(r.iPage===r.iTotalPages-1||r.iTotalPages===0){$("li:last",i[s]).addClass("disabled")}else{$("li:last",i[s]).removeClass("disabled")}}}}})
        $(document).ready(function(){oTable=$("#pedido-detalle").dataTable({bLengthChange:false,bFilter:false,sPaginationType:"bootstrap",sServerMethod:"POST",bProcessing:true,sAjaxSource:"' . $this->createUrl('pedido/detalleAjax',array('id'=>$model->idPedido)) . '",oLanguage:{sUrl:"' . Yii::app()->baseUrl .'/js/es_ES.txt"},aoColumnDefs:[{bVisible:false,aTargets:[0]},{bVisible:false,aTargets:[1]},{sClass:"coddoc",aTargets:[2]}],fnFooterCallback:function(e,t,n,r,i){var s=0;for(var o=0;o<t.length;o++){s+=t[o][4]*Number(t[o][5].replace("$",""))}var u=e.getElementsByTagName("th");var a=$("#valorAnillado").val()*nAnillados;var f=parseFloat(s+a).toFixed(2);var l=$("#Pedido_nCobrado").val();l=l!=""?parseFloat(l).toFixed(2):0;u[1].innerHTML="$"+parseFloat(a).toFixed(2);u[3].innerHTML="$"+f;$("#restante").val("$"+(f-l))}})})',CClientScript::POS_END);

		$this->render('update',array(
            'model'=>$model
            , 'usuarios' => $usuarios
            , 'estados' => $estados
            , 'sucursales' => $sucursales
            , 'documentos' => $documentos->searchTextLive()
            , 'articulos' => $articulos->searchTextLive()
            , 'valorAnillado' => $valorAnillado
            , 'materias' => $materias
            , 'profesores' => $profesores
            , 'carreras' => $carreras
            , 'cursos' => $cursos
            , 'instituciones' => $instituciones
            , 'filtroProfesores' => $documentos->filtroProfesores
            , 'filtroMaterias' => $documentos->filtroMaterias
            , 'filtroCarreras' => $documentos->filtroCarreras
            , 'filtroCursos' => $documentos->filtroCursos
            , 'filtroInstituciones' => $documentos->filtroInstituciones
        ));
	}

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     * @throws CHttpException
     */
	public function actionEliminar($id)
	{
		if (Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			$model = $this->loadModel($id);
            foreach ($model->documentos as $documento) {
                $documento->delete();
            }
            foreach ($model->articulos as $articulo) {
                $artTmp = Articulo::model()->findByPk($articulo->idArticulo);
                $artTmp->nStock = $artTmp->nStock + $articulo->nCantidad;
                $artTmp->update(array('nStock'));
                
                $articulo->delete();
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
		$model=new Pedido('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['Pedido'])) {
			$model->attributes=$_GET['Pedido'];
		}
        $estadosDependency = new CDbCacheDependency('SELECT MAX(idPedidoEstado) FROM pedidoestado');
        $estados = Pedidoestado::model()->cache(5000, $estadosDependency)->findAll();
        $estadosEditable = Editable::source($estados, 'idPedidoEstado', 'sDescripcion');
        $estados = CHtml::listData($estados, 'idPedidoEstado', 'sDescripcion');
        $clientes = array();
        $clientes[''] = 'Todos los clientes';
        $clientes['0'] = 'Cliente Mostrador';
        foreach (CHtml::listData(Usuario::model()->findAll(), 'idUsuario', 'fullNameDni') as $key => $value) {
            $clientes[$key] = $value;
        }
        
        $sucursalesdependency = new CDbCacheDependency('SELECT MAX(idSucursal) FROM sucursal');
        $sucursales = CHtml::listData(Sucursal::model()->cache(1000, $sucursalesdependency)->findAll(), 'idSucursal', 'sNombreSucursal');
        
		$this->render('index',array(
			'model'=>$model, 'estadosEditable'=>$estadosEditable, 'estados'=>$estados, 'clientes'=>$clientes, 'sucursales'=>$sucursales
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Pedido the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id, $with = '')
	{
        if(!empty($with))
            $model=Pedido::model()->with($with)->findByPk($id);
        else
            $model=Pedido::model()->findByPk($id);
		if ($model===null) {
			throw new CHttpException(404,'La pagina solicitada no existe.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Pedido $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax']==='pedido-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    public function actionDetalleAjax() {
        if(isset($_GET['id'])) {
            $model = $this->loadModel((int) $_GET['id'], array('documentos', 'articulos'));
            $data = array();
            $aDocumentos = array();
            $aArticulos = array();

            $index = 1;

            foreach($model->documentos as $documento) {
                $documentoHijo = Documento::model()->findByPk($documento->idDocumento);

                array_push($aDocumentos,
                    array(
                        $index,
                        "doc",
                        $documento->idDocumento,
                        $documentoHijo->sTitulo,
                        $documento->nCopias,
                        '$' . $documento->nValorUnitario,
                        '$' . $documento->nValorNeto,
                        '<span class="anill">'.$documento->nAnillado.'</span>',
                        '<input type="checkbox" value="8" name="ent" id="ent"' . ($documento->bEntregado == 1 ? " checked" : "") . '>',
                        ''//'<a href="javascript:void(0)" onclick="fnRemoveRow(' . ($index - 1) . ')" data-original-title="Quitar"><img src="' . Yii::app()->baseUrl.'/images/remove.png" alt="Quitar"/></a>'
                        ));
                $index++;
            }

            foreach($model->articulos as $articulo) {
                $articuloHijo = Articulo::model()->findByPk($articulo->idArticulo);

                array_push($aArticulos,
                    array(
                        $index,
                        "art",
                        "a" . $articulo->idArticulo,
                        $articuloHijo->sDescripcion,
                        $articulo->nCantidad,
                        '$' . $articulo->nValorUnitario,
                        '$' . $articulo->nValorNeto,
                        0,
                        '<input type="checkbox" value="8" name="ent" id="ent"' . ($articulo->bEntregado == 1 ? " checked" : "") . '>',
                        ''
                        ));
                $index++;
            }

            $data = array('aaData'=>array_merge($aDocumentos, $aArticulos));
            echo CJSON::encode($data);
        }
    }

    public function actionDetalleAjaxGrid(){
        if(isset($_GET['id'])) {
            $model = $this->loadModel((int) $_GET['id'], array('documentos', 'articulos'));

            $data = array();
            $aDocumentos = array();
            $aArticulos = array();

            $index = 1;

            foreach($model->documentos as $documento) {
                $documentoHijo = Documento::model()->findByPk($documento->idDocumento);

                array_push($aDocumentos,
                    array(
                        'id' => CJSON::encode(array('id'=>$documento->idDocumento, 't'=>'Documento', 'idP'=>$documento->idPedido)),
                        'cod' => $documento->idDocumento,
                        'tipo' => 'Documento',
                        'estado' => $documento->idEstado,
                        'idPedido' => $documento->idPedido,
                        'desc' => $documentoHijo->sTitulo,
                        'cant' => $documento->nCopias,
                        'precio' => $documento->nValorUnitario,
                        'anill' => $documento->nAnillado,
                        'ent'=> $documento->bEntregado,
                        'subtotal'=>$documento->nValorNeto + $documento->nValorAnillados 
                        )
                    );
                $index++;
            }

            foreach($model->articulos as $articulo) {
                $articuloHijo = Articulo::model()->findByPk($articulo->idArticulo);

                array_push($aArticulos,
                    array(
                        'id' => CJSON::encode(array('id'=>$articulo->idArticulo, 't'=>'Articulo', 'idP'=>$articulo->idPedido)),
                        'cod' => 'a'.$articulo->idArticulo,
                        'tipo' => 'Articulo',
                        'estado' => false,
                        'idPedido' => $articulo->idPedido,
                        'desc' => $articuloHijo->sDescripcion,
                        'cant' => $articulo->nCantidad . " - (" . $articuloHijo->nStock . " Stock Restante)",
                        'precio' => $articulo->nValorUnitario,
                        'anill' => 'No',
                        'ent'=> $articulo->bEntregado,
                        'subtotal'=>$articulo->nValorNeto
                    )
                );
                $index++;
            }

            $data = new CArrayDataProvider(array_merge($aDocumentos, $aArticulos));

            $this->renderPartial('_pedidosDetalles', array('id'=>$model->idPedido, 'data'=>$data), false, true);
        }
    }

    public function actionGetEstadosDocumentos() {
        //$dependency = new CDbCacheDependency('SELECT MAX(idPedidoDocumentoEstado) FROM pedidodocumentoestado');
        //echo CJSON::encode(Editable::source(Pedidodocumentoestado::model()->cache(86400, $dependency)->findAll(), 'idPedidoDocumentoEstado', 'sDescripcion')); 
        echo '[{"value":"1","text":"En Proceso"},{"value":"2","text":"Impreso"}]';
    }

    public function actionUpdatePedido() {
        if(isset($_POST)) {
            if($_POST['name'] == 'estado') {
                $model=null;

                $data = $_POST['pk'];

                $pedido = Pedido::model()->with('documentos')->findByPk((int) $data['idP']);
                if($pedido instanceof Pedido) {
                    $todosImpresos = true;

                    foreach ($pedido->documentos as $item) {
                        if($item->idDocumento == (int) $data['id']) {
                            if($item->ChangeEstado((int) $_POST['value'])){
                                $item->update(array('idEstado'));
                            } else {
                                $todosImpresos = false;
                            }
                        } else if($item->idEstado == 1) {
                            $todosImpresos = false;
                        }
                    }

                    if($todosImpresos) {
                        $pedido->idEstado = 3;
                        $pedido->update(array('idEstado'));
                    }
                } else {
                    throw new CHttpException(404, 'No se pudo obtener el objeto solicitado.');
                }
            } else {
                throw new CHttpException(404, 'Request invalida.');
            }
        }
    }

    public function actionUpdatePedidoDetalle() {
        if(isset($_POST)) {
            if($_POST['name'] == 'ent') { // Entregado
                $data = $_POST['pk'];
                $with = $data['t'] == 'Documento' ? 'documentos' : 'articulos';
                $pedido = Pedido::model()->with($with)->findByPk((int) $data['idP']);

                $todosEntregados = true;
                $todosImpresos = true;

                if($data['t'] == 'Documento') { // Documento
                    foreach ($pedido->documentos as $item) {
                        if($item->idDocumento == (int) $data['id']) {
                            if($item->ChangeEntregado(isset($_POST['value']) ? 1 : 0)) {
                                $item->update(array('idEstado', 'bEntregado'));
                            } else {
                                $todosEntregados = false;
                                if($item->idEstado == 1) {
                                    $todosImpresos = false;
                                }
                            }
                        } else {
                            if($item->bEntregado == 0) {
                                $todosEntregados = false;
                                if($item->idEstado == 1) {
                                    $todosImpresos = false;
                                }
                            }
                        }
                    }

                    if($todosEntregados) {
                        $pedido->idEstado = 5;
                        $pedido->update(array('idEstado'));
                    } else if($todosImpresos) {
                        $pedido->idEstado = 3;
                        $pedido->update(array('idEstado'));
                    }

                }
                elseif($data['t'] == 'Articulo') { // Articulo
                    foreach ($pedido->articulos as $art) {
                        if($art->idArticulo == (int) $data['id']) {
                            $art->bEntregado = (isset($_POST['value']) ? 1 : 0);
                            $art->update(array('bEntregado'));
                        }
                    }
                }
            }
        }
        else
            throw new CHttpException(404, 'Request invalida.');
    }

    public function actionTerminar($id) {
        $pedido = $this->loadModel($id);
        if($pedido instanceof Pedido) {
            $pedido->idEstado = 5;
            $pedido->nFaltante = 0;
            $pedido->update(array('idEstado', 'nFaltante'));

            foreach ($pedido->documentos as $documento) {
                $documento->bEntregado = 1;
                $documento->idEstado = 2;
                $documento->update(array('bEntregado', 'idEstado'));
            }

            Yii::app()->user->setFlash('success', 'Pedido terminado con éxito.');
        }
        $this->redirect(array('index'));
    }

    public function actionArmar($id) {
        $pedido = $this->loadModel($id);
        if($pedido instanceof Pedido) {
            $pedido->idEstado = 3;
            $pedido->update(array('idEstado'));

            foreach ($pedido->documentos as $documento) {
                $documento->ChangeEntregado(0);
                $documento->ChangeEstado(2);
                $documento->update(array('bEntregado', 'idEstado'));
            }

            Yii::app()->user->setFlash('success', 'Pedido armado con éxito.');
            $this->redirect(array('index'));
        }
    }

    public function actionUpdatePedidoEstado() {
        if(isset($_POST) && isset($_POST['value']) && isset($_POST['value'])) {
            if($_POST['name'] == 'idEstado') {
                $pedido = Pedido::model()->with('documentos')->findByPk((int) $_POST['pk']);
                if($pedido instanceof Pedido) {
                    $nuevoEstado = (int) $_POST['value'];
                    if($nuevoEstado > $pedido->idEstado) {
                      $pedido->idEstado = $nuevoEstado;
                      $pedido->update(array('idEstado'));
                    } else {
                      throw new CHttpException(400, 'No puede volver un pedido a un estado anterior.');
                    }

                    foreach ($pedido->documentos as $documento) {
                        switch ($pedido->idEstado) {
                            case 1:
                            case 2:
                                $documento->ChangeEntregado(0);
                                $documento->ChangeEstado(1);
                                break;
                            case 3:
                            case 4:
                                $documento->ChangeEntregado(0);
                                $documento->ChangeEstado(2);
                                break;
                            case 5:
                                $documento->ChangeEntregado(1);
                                $documento->ChangeEstado(2);
                                break;
                        }
                        $documento->update(array('bEntregado', 'idEstado'));
                    }

                }
            }
        }
        else
            throw new CHttpException(404, 'Request invalida.');
    }

    public function actionComprobante($id){
        $pedido = $this->loadModel($id, array('documentos', 'articulos', 'sucursal', 'usuario'));
        $this->layout='//layouts/comprobante';

        if($pedido instanceof Pedido) {
            //$this->render('_comprobante', array('pedido'=>$pedido));
            error_reporting(E_ERROR);
            $html2pdf = Yii::app()->ePdf->HTML2PDF();

            $html2pdf->pdf->SetDisplayMode('fullwidth', 'continuous', 'UseNone');
            $html2pdf->WriteHTML($this->render('_comprobante', array('pedido'=>$pedido), true), false);
            $html2pdf->Output('Comp. Pedido ' . $pedido->idPedido.'.pdf');
        }
    }

    public function actionEstadoPago($id) {
        $model = $this->loadModel($id, 'pago');
        if($model->pago->idFormaPago == 1) { // Dineromail
            if($model->pago->bFinalizado == 1) { // Muestro información que ya tengo

            } else { // Pido estado del pago a Dineromail
                Yii::app()->clientScript->registerScript('loadComp',
                '$( document ).ready(function() {
                    var xmlString = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><REPORTE><NROCTA>' . Yii::app()->params['dmMerchant'] . '</NROCTA><DETALLE><CONSULTA><CLAVE>' . Yii::app()->params['dmPass'] . '</CLAVE><TIPO>1</TIPO><OPERACIONES><ID>' . $model->pago->idPago . '</ID></OPERACIONES></CONSULTA></DETALLE></REPORTE>";
                    $.ajax({
                        type : "POST",
                        url : "https://argentina.dineromail.com/Vender/Consulta_IPN.asp",
                        data : {
                            method : "Save",
                            data : xmlString
                        },
                        contentType: "text/xml",
                        processData: false,
                        cache : false,
                        success : parseResponse(data)
                    });
                    
                    function parseResponse(data) {
                        var xmlDoc = $.parseXML( xml ),
                        $xml = $( xmlDoc ),
                        $estadoReporte = $xml.find( "ESTADOREPORTE" );
                        //http://foro.dineromail.com/viewtopic.php?f=53&t=78
                    }

                });'
                );
                $this->render('estadoPagoDM');
            }
        }
    }

}