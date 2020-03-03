<?php
// *********** 
// Mauricio Bermudez Vargas 29/11/2019 10:12 a.m.
// ***********

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 300);
error_reporting(E_ALL);
ini_set('display_errors', false);        
ini_set('html_errors', true);

try
{

} catch (PDOException $e) {		
	echo "Error al conectar con la base de datos: " . $e->getMessage() . "\n";
	exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="autor" content="Mauricio Bermúdez Vargas"/>
    <meta name="viewport" content="width=device-width"/>
    <link rel="stylesheet" type="text/css" href="css/css_marcam.css">
    <title>Marca</title>
    <script async src="js/zxing.js"></script>
    <script type="text/javascript" src="js/camara.js?<?php echo rand(); ?>"></script>
    <script type="text/javascript" src="jq/jquery-3.2.1.min.js"></script>
</head>
<body>
<div id="menu">
	<a id="salir" href="seleccion.php"></a>
	<a id="add" href="busca_Estudiante.php?tipo=<?php echo $getTipoMarca; ?>"></a>	
</div>
<div id="mainArea">
    <!-- Contenedor de proceso marca -->
    <div id="contenedorMarca">
    <video id="video" autobuffer></video>
    <canvas id="canvas" width="240" height="320"></canvas>
    <img id="photo">
    <div id="containerTxt">
        <div>Cédula: <span id="dbr"></span></div>        
    </div> 
    <div class="select">
        <label for="videoSource">Video source: </label><select id="videoSource"></select>
    </div>    
    <div id="containerButton">           
        <button id="startbutton">¡Activa Camara!</button>    
        <button id="stopbutton"></button>
    </div>    	    
    </div>
</div>
<div id="statusBar">
    <a id="linkHogar" href="https://www.lasesperanzas.ed.cr">lasesperanzas.ed.cr</a>
    <a id="linkWappcom"href="https://www.wappcom.net">wappcom.net</a>                                       
</div>

<script language='javascript'>

var streaming = false,
  video        = document.querySelector('#video'),
  canvas       = document.querySelector('#canvas'),
  photo        = document.querySelector('#photo'),  
  stopbutton  = document.querySelector('#stopbutton'),
  barcode_result = document.getElementById('dbr'),
  videoSelect = document.querySelector('select#videoSource'),
  width = 240,
  height = 0,
  mobileVideoWidth = 240, 
  mobileVideoHeight = 320;

var ZXing = null;
var decodePtr = null;
var fotobar = null;


startbutton.addEventListener('click', function(ev){ camaraStart(); ev.preventDefault();}, false);  
stopbutton.addEventListener('click', function(ev){ fotobar = camaraStop(); barcode(); ev.preventDefault();}, false);

//camaraStart();

function barcode() {
    if (ZXing == null) {
        stopbutton.disabled = false;
        alert("Error con lector de barra!");
        return;
    }
    barcode_result.textContent = "";
                   
    return 0;
}

var decodeCallback = function (ptr, len, resultIndex, resultCount) {
  var result = new Uint8Array(ZXing.HEAPU8.buffer, ptr, len);
  console.log(String.fromCharCode.apply(null, result));
  barcode_result.textContent = String.fromCharCode.apply(null, result);
};

var tick = function () {
  if (window.ZXing) {
    ZXing = ZXing();
    decodePtr = ZXing.Runtime.addFunction(decodeCallback);
  } else {
    setTimeout(tick, 10);
  }
};

tick();
navigator.mediaDevices.enumerateDevices().then(gotDevices).catch(handleError);

function gotDevices(deviceInfos) {
  for (var i = deviceInfos.length - 1; i >= 0; --i) {
    var deviceInfo = deviceInfos[i];
    var option = document.createElement('option');
    option.value = deviceInfo.deviceId;    
    if (deviceInfo.kind === 'videoinput') {
      option.text = deviceInfo.label || 'camera ' +
        (videoSelect.length + 1);
      videoSelect.appendChild(option);
    } else {455
      console.log('Found one other kind of source/device: ', deviceInfo);
    }
  }
}

function handleError(error) {
  console.log('Error: ', error);
}

$('#salir').html('<img src="img/salir.png">');
$('#add').html('<img src="img/add.png">');
$('#stopbutton').html('<img src="img/camaraboton.png">');

</script>

</body>
</htm>
