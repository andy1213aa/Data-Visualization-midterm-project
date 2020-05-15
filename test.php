<?php
function getCommodityByCategory($category){
    $link = mysqli_connect("localhost", "root", "", "pharmacy") or die("無法開啟MySQL資料庫連接!<br/>");
    $sql = "SELECT 藥品名稱 ,等級,SUM(銷售額) 總銷售額 FROM pharmacy WHERE 說明 = " .$category. "GROUP BY 藥品名稱 ORDER BY 總銷售額 DESC";
    $commodity = mysqli_query($link, $sql);
    if(!$commodity){
        echo ("Error: ".mysqli_error($link));
        exit();
    }
    $test = array();
    while($row = mysqli_fetch_array($commodity)){
        $tmp = array('Commodity' => $row['藥品名稱'], 'Sale' => $row['總銷售額'], 'Rank'=>$row['等級']);
        array_push($test, $tmp);
        
    }
    return $test;
}

$c = ["'牙線'"];
$a = getCommodityByCategory($c[0]);
$json = json_encode($a);
    echo $json;
?>
