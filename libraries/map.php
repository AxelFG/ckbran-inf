<?php 

/*

KEY FOR 151.248.116.136 ADDRESS BELOW:


KEY FOR CKB-RAN.RU DOMAIN BELOW:


KEY FOR CKBRAN.RU DOMAIN BELOW:

*/

if ($_SERVER['HTTP_HOST'] == 'ckb-ran.ru')

	$key = 'AD38ulEBAAAAQqv6VQIAESbHpF4GtsUY_jksX4kkLQ5vMlQAAAAAAAAAAACdLbIgwcKTnmW7Wdctl9tYWD70vQ==';

elseif ($_SERVER['HTTP_HOST'] = 'ckbran.ru')

	$key = 'APUAuVEBAAAAyD3sRQIAP6j5iMjvMmwQKlrFzxPKrhkQ2vwAAAAAAAAAAADuTlangSYYYoNq2Yrh-DW7aJuxsg==';
	
else

	$key = 'ANYBuVEBAAAAl_eGFwQAgrAVNllYWopSvzmjvJXh46Avgf0AAAAAAAAAAAAJvTVwgKQm_oSvLFBxEmBmyffDqg==';


if ($_GET['r'] == 1) {
	$address = 'Москва, Литовский бул., 1а';
 	$title = 'Центральная клиническая больница Российской академии наук';   
}
elseif ($_GET['r'] == 2) {
	$address = 'Москва, ул. Фотиевой, 12, корп.3';
 	$title = 'Поликлиника № 3 ЦКБ РАН';
}
else {
	$address = '';
    $title = '';	
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>На карте</title>
 
<style>
		* {
		margin: 0;
		padding: 0;
		}
</style>
 
<script src="http://api-maps.yandex.ru/1.1/index.xml?key=<?php echo $key ?>"
	type="text/javascript"></script>
<script src="http://api-maps.yandex.ru/2.0/?load=package.standard&mode=debug&lang=ru-RU" type="text/javascript"></script>
    <script type="text/javascript">
        window.setTimeout(function () {
            var map = new YMaps.Map(document.getElementById("YMapsID"));
 
			map.addControl(new YMaps.TypeControl());//Тип карты, кнопочки Схема, Гибрид, Спутник
			map.addControl(new YMaps.ToolBar());//Тулбар, кнопки Рука, Лупа, Линейка
			map.addControl(new YMaps.Zoom());	
 
 
			// Создание объекта геокодера
            var geocoder = new YMaps.Geocoder("<?php echo $address ?>");
            // По завершению геокодирования инициализируем карту первым результатом
            YMaps.Events.observe(geocoder, geocoder.Events.Load, function (geocoder) {
                if (geocoder.length()) {
              map.setBounds(geocoder.get(0).getBounds());
 
           // Создание метки с всплывающей подсказкой
            var placemark = new YMaps.Placemark(map.getCenter(), {hasHint: 1});           
 
            // Добавление метки на карту
            map.addOverlay(placemark);
 
           placemark.openBalloon('<div style="text-align:center; width: 200px; "><strong><?php echo $title ?></strong></div>'); 
 
                }
            });
}, 0);	
</script>

</head>
 
<body>
 
 <div id="YMapsID" style="width:580px;height:380px"></div>
 
</body>
</html>