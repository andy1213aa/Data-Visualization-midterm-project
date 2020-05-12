<?php

$store = 9;
$link = mysqli_connect("localhost", "root", "", "pharmacy") or die("無法開啟MySQL資料庫連接!<br/>");
$sql = "SELECT 說明, 等級,SUM(銷售額) 總銷售額 FROM pharmacy WHERE 銷貨倉= ".$store. " GROUP BY 說明 ORDER BY 總銷售額 DESC";
$result = mysqli_query($link, $sql);
if(!$result){
    echo ("Error: ".mysqli_error($link));
    exit();
}
$Category = array();
$Sale = array();
$allData = array();
while($row = mysqli_fetch_array($result)){
    $tmp = array('Category' => $row['說明'], 'Sale' => $row['總銷售額']);
    array_push($allData, $tmp);
    
}


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
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pharmacy</title>
    <script src="https://d3js.org/d3.v4.min.js"></script>
    <script src="https://d3js.org/d3-color.v1.min.js"></script>
    <script src="https://d3js.org/d3-interpolate.v1.min.js"></script>
    <script src="https://d3js.org/d3-scale-chromatic.v1.min.js"></script>
</head>

<body>  
    
    </body>
    </html>
    <script>
        
    var test = <?php echo json_encode(getCommodityByCategory("'牙線'")); ?>;
    
    var dataset = <?php echo json_encode($allData); ?> ;
    
    const width = 1000;
    const height = 1000;
    const svg = d3.selectAll('body')
        .append('svg')
        .attr('width', width)
        .attr('height', height)
        .append('g')
        .attr('transform', `translate(${width / 2}, ${height / 2})`)
        .attr('id', 'pieChart');

    function drawPie(dateset, pieNumber){
        var pieNumber = pieNumber;
        var piedata = Object.assign([], dateset);
        piedata = piedata.slice(0, pieNumber);
        
        var pieGenerator = d3.pie()
            .value(function(d){
                return d.Sale;
            })
            .sort(function(a, b){
                return a.Category.localeCompare(b.Category);
            });
     
        
        var arcData = pieGenerator(piedata);
        var colorScale = d3.scaleOrdinal()
            .domain(piedata.map(function(d){
                
                return d.Category;
            }))
            .range(d3.schemeSet3);
        var arcGenerator = d3.arc()
            .innerRadius(60)
            .outerRadius(100);
        var g = d3.select('#pieChart')
            .append('g')
        
        g.selectAll('path')
            .data(arcData)
            .enter()
            .append('path')
            .attr('d', arcGenerator)
            .style('fill', function(d){
                return colorScale(d.data.Category);
            });

    }
    drawPie(dataset, 10);
    
</script>