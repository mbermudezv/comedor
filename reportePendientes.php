<?php
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 300);
error_reporting(E_ALL);
ini_set('display_errors', false);
ini_set('html_errors', true);

require_once("sql/select.php");

try {

    $getfecDesde=null;
    $getfecHasta=null;

    if (isset($_GET['fechaDesde']) and isset($_GET['fechaHasta'])) {
        $getfecDesde = $_GET['fechaDesde'];
        $getfecHasta = $_GET['fechaHasta'];    
        $db = new Select();
        $rs = $db->conReporteCuentasPendientes($getfecDesde,$getfecHasta);    
    }	
} catch (PDOException $e) {
echo "Error al conectar con la base de datos: " . $e->getMessage() . "\n";
exit;	
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="autor" content="Mauricio Bermúdez Vargas" />
<meta name="viewport" content="width=device-width" />
<link rel="stylesheet" type="text/css" href="css/css_Reporte.css">
<title>Reporte</title>
<script type="text/javascript" src="jq/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="jq/jquery-ui.js"></script>
<script>
$.datepicker.regional['es'] = {
 closeText: 'Cerrar',
 prevText: '< Anterior',
 nextText: '  Siguiente >',
 currentText: 'Hoy',
 monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
 monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
 dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
 dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
 dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
 weekHeader: 'Sm',
 dateFormat: 'dd-mm-yy',
 firstDay: 1,
 isRTL: false,
 showMonthAfterYear: false,
 yearSuffix: ''
 };
 $.datepicker.setDefaults($.datepicker.regional['es']);

$( function() {
    $('.datepicker').datepicker(); 
});

</script>
</head>
<body>
<div class="menu">
    <a id="menu1" class="salir" href="principal.php"></a>    
</div>

<form action="" id="formulario" method="get">
    <div id="cont1" class="contDate">
        <div id="cont2" class="itemDate">
            <input  id="inp1" type="text" name="fechaDesde" maxlength="10" autocomplete="off" class="datepicker" placeholder="  Fecha inicio..." readonly="readonly">
            <input id="inp2" type="text" name="fechaHasta" maxlength="10" autocomplete="off" class="datepicker" placeholder="  Fecha fin..." readonly="readonly">                    
        </div> 
        <div id="btn1" class="btnbuscar" onclick="document.getElementById('formulario').submit();"></div>
    </div>
</form>
<div class="menu_export">
    <div class="imprimir"></div>
    <a id="hyp_excel" class="excel" href="reporte_exportarPendientes.php?fechaDesde=<?php echo $getfecDesde;?>&fechaHasta=<?php echo $getfecHasta;?>&t=1"></a>
    <a id="hyp_word" class="word" href="reporte_exportarPendientes.php?fechaDesde=<?php echo $getfecDesde;?>&fechaHasta=<?php echo $getfecHasta;?>&t=2"></a>
</div>
<div id="enc1" class="encabezado">
    <div id="log1" class="logo"></div>
    <div id="tit1" class="titulo">Informe de almuerzos por cobrar</div>
</div>

<div id="enc5" class="tabla_encabezado_rango">
    <div id="enc6" class="tabla_encabezado_rango_item">Desde:</div>
    <div id="enc6" class="tabla_encabezado_rango_item"><?php echo $getfecDesde; ?></div>
    <div id="enc6" class="tabla_encabezado_rango_item">Hasta:</div>
    <div id="enc7" class="tabla_encabezado_rango_item"><?php echo $getfecHasta; ?></div>
</div>
<div id="tab1" class="tabla_titulo">
    <div id="tab1" class="tabla_titulo_item">Fecha</div>
    <div id="tab15" class="tabla_titulo_item">Nombre</div>
    <div id="tab2" class="tabla_titulo_item">Monto</div>
</div>
<?php
if(!empty($rs)) {
$total=null;
foreach($rs as $rsItemCuenta) {
    $total= $total + $rsItemCuenta["Monto"];
    $nombrecompleto = $rsItemCuenta["Cliente_Nombre"] . ' ' . $rsItemCuenta["Cliente_Apellido1"] . ' ' . $rsItemCuenta["Cliente_Apellido2"];
    ?>  
    <div id="tab3" class="tabla">      
        <div id="tab4" class="tabla_item"><?php echo $rsItemCuenta["Fecha"]; ?></div>
        <div id="tab16" class="tabla_item"><?php echo $nombrecompleto; ?></div>
        <div id="tab5" class="tabla_item"><?php echo $rsItemCuenta["Monto"]; ?></div>
    </div>    
<?php
} 
$rs = null;
$db = null;
}
?>
<div id="tab6" class="tabla_titulo">
    <div id="tab7" class="tabla_titulo_item">Total:</div>
    <div id="tab8" class="tabla_titulo_item"><?php echo $total; ?></div>
</div>
<div id="tab9" class="contendor_linea_firma">
    <div id="tab11" class="linea_firma">-------------------------</div>
</div>
<div id="tab12" class="contenedor_nombre_firma">    
    <div id="tab14" class="item_nombre_firma"><?php echo "Por Comité de nutrición"; ?></div>
</div>
 <script>
$(document).ready(function() {
    $('.menu_export').on('click', ".imprimir", function () {
        $('.menu').hide();
        $('.containerNombre').hide();
        $('.contDate').hide();
        $('.menu_export').hide();
        window.print();
        $('.menu').show();
        $('.containerNombre').show();
        $('.contDate').show();
        $('.menu_export').show();        
    });
});
$('.salir').html('<img src="img/lista.png">');
$('.btnbuscar').html('<img src="img/refresh.png">');
$('.imprimir').html('<img src="img/print.png">');
$('.excel').html('<img src="img/excel.png">');
$('.word').html('<img src="img/word.png">');
$('.pdf').html('<img src="img/pdf.png">');
$('.logo').html('<img src="img/escudo.png">');
</script>

</body>
</html>