<?php
    function getCategory($select, $store, $timeFrom, $timeTo){
            
        $link = mysqli_connect("localhost:3307", "root", "", "pharmacy") or die("無法開啟MySQL資料庫連接!<br/>");
        #$sql = 'SELECT 說明, 等級,SUM(銷售額) 總銷售額, SUM(交易後庫存量) 總交易後庫存量,  SUM(售價) 總售價, SUM(銷售量) 總銷售量 FROM pharmacy WHERE 銷貨倉 =' .$store. ' GROUP BY 說明 ORDER BY ' .$order. ' DESC';
        $sql = 'SELECT 說明, SUM('.$select.') '.$select.' FROM pharmacy WHERE 銷貨倉 = '.$store.' AND 銷貨日期 >= '."'$timeFrom'".' AND 銷貨日期 <= '."'$timeTo'".' GROUP BY 說明 ORDER BY '.$select.' DESC';
        $result = mysqli_query($link, $sql);
        if(!$result){
            echo ("Error: ".mysqli_error($link));
            exit();
        }
      
        $allData = array();
        while($row = mysqli_fetch_array($result)){
            $tmp = array('key' => $row['說明'], 'value' => $row[$select]);
            array_push($allData, $tmp);
            
        }
        return $allData;
    }
    function getSpecialKey($select, $store, $timeFrom, $timeTo){
            
        $link = mysqli_connect("localhost:3307", "root", "", "pharmacy") or die("無法開啟MySQL資料庫連接!<br/>");
        #$sql = 'SELECT 說明, 等級,SUM(銷售額) 總銷售額, SUM(交易後庫存量) 總交易後庫存量,  SUM(售價) 總售價, SUM(銷售量) 總銷售量 FROM pharmacy WHERE 銷貨倉 =' .$store. ' GROUP BY 說明 ORDER BY ' .$order. ' DESC';
        $sql = 'SELECT COUNT('.$select.') 總數, '.$select.' FROM pharmacy WHERE 銷貨倉 = '.$store.' AND 銷貨日期 >= '."'$timeFrom'".' AND 銷貨日期 <= '."'$timeTo'".' GROUP BY ' .$select. ' ORDER BY '.$select.' DESC';
        $result = mysqli_query($link, $sql);
        if(!$result){
            echo ("Error: ".mysqli_error($link));
            exit();
        }
      
        $allData = array();
        while($row = mysqli_fetch_array($result)){
            $tmp = array('key' => $row[$select], 'value' => $row['總數']);
            array_push($allData, $tmp);
            
        }
        return $allData;
    }

    function getCommodityByCategory($key, $select, $store, $timeFrom, $timeTo){
        $link = mysqli_connect("localhost:3307", "root", "", "pharmacy") or die("無法開啟MySQL資料庫連接!<br/>");
        $sql = 'SELECT 藥品名稱 ,SUM('.$select.') '.$select.' FROM pharmacy WHERE 說明 = ' ."'$key'". ' AND 銷貨倉 = '.$store.' AND 銷貨日期 >= '."'$timeFrom'".' AND 銷貨日期 <= '."'$timeTo'".' GROUP BY 藥品名稱 ORDER BY '.$select.' DESC';
        $commodity = mysqli_query($link, $sql);
        if(!$commodity){
            echo ("Error: ".mysqli_error($link));
            exit();
        }
        $allData = array();
        while($row = mysqli_fetch_array($commodity)){
            $tmp = array('key' => $row['藥品名稱'], 'value' => $row[$select]);
            array_push($allData, $tmp);
            
        }
        return $allData;
    }

    function getAprioriFrimCommodity($key){
        $link = mysqli_connect("localhost:3307", "root", "", "pharmacy") or die("無法開啟MySQL資料庫連接!<br/>");
        $sql = 'SELECT rs , confidence FROM apriori WHERE ls = ' ."'$key'". ' ORDER BY confidence DESC';
        $commodity = mysqli_query($link, $sql);
        if(!$commodity){
            echo ("Error: ".mysqli_error($link));
            exit();
        }
        $allData = array();
        while($row = mysqli_fetch_array($commodity)){
            $tmp = array('key' => $row['rs'], 'value' => $row['confidence']);
            array_push($allData, $tmp);
            
        }
        return $allData;
    }
    header('Content-Type: application/json; charset=UTF-8');

    
    if( !isset($_POST['functionname']) ) { $aResult['error'] = 'No function name!'; }

    if( !isset($_POST['arguments']) ) { $aResult['error'] = 'No function arguments!'; }

    if( !isset($aResult['error']) ) {

        switch($_POST['functionname']) {
            case 'getCategory':
            if( !is_array($_POST['arguments']) ) {
                $aResult['error'] = 'Error in arguments!';
            }
            else {
                $aResult['result'] = getCategory($_POST['arguments'][0], $_POST['arguments'][1], $_POST['arguments'][2], $_POST['arguments'][3]);
            }
            break;
            case 'getSpecialKey':
                if( !is_array($_POST['arguments']) ) {
                    $aResult['error'] = 'Error in arguments!';
                }
                else {
                    $aResult['result'] = getSpecialKey($_POST['arguments'][0], $_POST['arguments'][1], $_POST['arguments'][2], $_POST['arguments'][3]);
                }
                break;
            case 'getCommodityByCategory':
                if( !is_array($_POST['arguments']) ) {
                    $aResult['error'] = 'Error in arguments!';
                }
                else {
                    $aResult['result'] = getCommodityByCategory($_POST['arguments'][0], $_POST['arguments'][1], $_POST['arguments'][2], $_POST['arguments'][3], $_POST['arguments'][4]);
                }
                break;
            case 'getAprioriFrimCommodity':
                if( !is_array($_POST['arguments']) ) {
                    $aResult['error'] = 'Error in arguments!';
                }
                else {
                    $aResult['result'] = getAprioriFrimCommodity($_POST['arguments'][0]);
                }
                break;
            default:
            $aResult['error'] = 'Not found function '.$_POST['functionname'].'!';
            break;
        }

    }

#echo json_encode(getSpecialKey('性別', 9, '2018-01-01', '2018-02-01'));
echo json_encode( $aResult);
    
// }
// 
?>

