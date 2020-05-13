<?php
    function getCategoryByStoreID($store){
            
        $link = mysqli_connect("localhost:3307", "mid", "", "pharmacy") or die("無法開啟MySQL資料庫連接!<br/>");
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
        return $allData;
    }

    function getCommodityByCategory($category){
        $link = mysqli_connect("localhost:3307", "mid", "", "pharmacy") or die("無法開啟MySQL資料庫連接!<br/>");
        $sql = "SELECT 藥品名稱 ,等級,SUM(銷售額) 總銷售額 FROM pharmacy WHERE 說明 = " ."'$category'". "GROUP BY 藥品名稱 ORDER BY 總銷售額 DESC";
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
    header('Content-Type: application/json; charset=UTF-8');

    
    if( !isset($_POST['functionname']) ) { $aResult['error'] = 'No function name!'; }

    if( !isset($_POST['arguments']) ) { $aResult['error'] = 'No function arguments!'; }

    if( !isset($aResult['error']) ) {

        switch($_POST['functionname']) {
            case 'getCategoryByStoreID':
            if( !is_array($_POST['arguments']) ) {
                $aResult['error'] = 'Error in arguments!';
            }
            else {
                $aResult['result'] = getCategoryByStoreID($_POST['arguments'][0]);
            }
            break;
            case 'getCommodityByCategory':
                if( !is_array($_POST['arguments']) ) {
                    $aResult['error'] = 'Error in arguments!';
                }
                else {
                    $aResult['result'] = getCommodityByCategory($_POST['arguments'][0]);
                }
                break;
            default:
            $aResult['error'] = 'Not found function '.$_POST['functionname'].'!';
            break;
        }

    }
    
echo json_encode($aResult);
    
// }
// 
?>

