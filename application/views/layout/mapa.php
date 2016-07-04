<!DOCTYPE html!>
<html lang=pt-br>
<head>
	<meta charset="UTF-8" >
<?php 
	//echo ( isset($includes) ? $includes : '' );
?>
<?php if ( isset($function) && $function == 'mapa' ) :?> 
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDOSBM0ZlyeGS3fva6P9zZRBW1G803vtJI&sensor=true"></script>
<script type="text/javascript">
function initialize() {
  var mapOptions = {
    zoom: 16,
    center: new google.maps.LatLng(<?php echo $mapa;?>),
    mapTypeId: google.maps.MapTypeId.ROADMAP
  }
  var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
   var image = '<?php echo 'http://www.guiasjp.com/imagens/imoveis_marcador.png';
  var myLatLng = new google.maps.LatLng(<?php echo $mapa;?>);
  var beachMarker = new google.maps.Marker({
      position: myLatLng,
      map: map,
      icon: image
  });
}
</script>  
<?php endif; ?>
</head>
<body <?php echo ( isset($function) && $function == 'mapa' ) ? 'onload="initialize()"' : '';?>  >
        <?php
        echo $conteudo;
        ?>
</body>
</html>
