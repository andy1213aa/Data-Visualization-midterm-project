<?php
	$place = "北京市";
	$link = mysqli_connect("127.0.0.1", "Midterm", "", "historical_climate") or die("無法開啟MySQL資料庫連接!<br/>");
	$sql = "SELECT * FROM pharmacy where place_provin = '".$place."'";
	$result = mysqli_query($link, $sql);
	if(!$result){
		echo ("Error: ".mysqli_error($link));
		exit();
	}
	$yearStart = array();
	$yearEnd = array();
	$seasStart = array();
	$seasEnd = array();
	$provin = array();
	$city = array();
	$event = array();
	$source = array();
	while($row = mysqli_fetch_array($result)){
		array_push($yearStart,$row['year_greg_ed']);
		array_push($yearEnd,$row['year_greg_ed']);
		array_push($seasStart,$row['seas_lunar_st']);
		array_push($seasEnd,$row['seas_lunar_ed']);
		array_push($provin,$row['place_provin']);
		array_push($city,$row['place_city']);
		array_push($event,$row['event_code']);
		array_push($source,$row['source']);
	}
	$allData = array(  'yearStart' => $yearStart,
					'yearEnd' => $yearEnd,
					'seasStart' => $seasStart,
					'seasEnd' => $seasEnd,
					'provin' => $provin,
					'city' => $city,
					'event' => $event,
					'source' => $source
	);
	$json = json_encode($allData);
	echo $json;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Historical Climate</title>

</head>

<body>  
</body>
</html>

