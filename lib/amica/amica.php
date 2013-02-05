<?php

include('simple_html_dom.php');
date_default_timezone_set('Europe/Helsinki');
error_reporting(E_ALL);
ini_set("display_errors", 1);
$html = "";
if(isset($_GET['lang']) && $_GET['lang'] == 'fi'){
    $html = str_get_html(file_get_contents('http://www.amica.fi/Ravintolat/Amica-ravintolat/Ravintolat-kaupungeittain/Helsinki/Arcada--Nylands-svenska-yrkeshogskola1/'));
    $lang = "fi";
}else if(isset($_GET['lang']) && $_GET['lang'] == 'en'){
    $html = str_get_html(file_get_contents('http://www.amica.fi/en/Ravintolat/Amica-restaurants/Areas/Helsinki/In-English/'));
    $lang = "en";
}else{
    $html = str_get_html(file_get_contents('http://www.amica.fi/sv/Restauranger/Amica-restauranger/Alla-orter/Helsingfors/Arcada--Nylands-svenska-yrkeshogskola/'));
    $lang = "se";
}
$infoArrOrig = array(
    "se" => array(
        "*" => "Må gott",
        "M" => "Mjölkfri",
        "G" => "Glutenfri",
        "L" => "Laktosfri",
        "VL" => "Laktosfattig"
    ),
    "fi" => array(
        "*" => "Voi hyvin",
        "M" => "Maidoton",
        "G" => "Gluteeniton",
        "L" => "Laktoositon",
        "VL" => "Vähälaktoosinen"
    ),
    "en" => array(
        "*" => "Feel well",
        "M" => "Milk-free",
        "G" => "Gluten-free",
        "L" => "Lactose-free",
        "VL" => "Low in lactose"
    ),
);
$ruokaLista = trim($html->find('body .ContentArea', 2)->innertext);
$ruokaLista = preg_replace(array(
    '/<br \/>/',
    '/&nbsp;/',
    '/<p>/',
    '/<\/p>/',
    '/(\d)([^\d,])/',
    '/(\d{1})£/i',
    '/(\()(['.join("|",array_keys($infoArrOrig['se'])).'|,| ]+)(\))/i',
    '/([A-Za-z])(\d)/'
        ), array(
    '|',
    '',
    '|',
    '|',
    '$1£$2',
    '$1)£',
    '{$2}',
    '$1{}$2'
        ), $ruokaLista);
//echo $ruokaLista;
$arr = array();
$day = "";
$split = 1;
foreach (preg_split("/\|/", $ruokaLista) as $i => $line) {
    if (trim($line) == "") {
        $split = 1;
    } else if ($split) {
        $day = $line;
        $split = 0;
    } else {
        if(!isset($arr[trim($day)])){$arr[trim($day)] = "";}
        $arr[trim($day)] .= $line;
    }
}
array_pop($arr);
$days = array_keys($arr);
foreach($arr as $day => $line){
    $arr[$day] = array();
    $line = preg_split("/£/", $line, 0, PREG_SPLIT_NO_EMPTY);
    foreach($line as $i => $var){
        $line[$i] = array();
        foreach(preg_split('/\}/', trim($var), 0, PREG_SPLIT_NO_EMPTY) as $j => $part){
            if(preg_match('/\{/', $part)){
                $part = preg_split('/\{/', trim($part), 0, PREG_SPLIT_NO_EMPTY);
                $infoArr = array();
                if(isset($part[1])){
                    foreach(preg_split("/, /",trim($part[1]," {}"), 0, PREG_SPLIT_NO_EMPTY) as $info){
                        $infoArr[$info] = (isset($infoArrOrig[$lang][$info]))?$infoArrOrig[$lang][$info]:"";
                    }
                }
                $line[$i]['parts'][] = array(
                    'name' => trim($part[0]),
                    'info' => $infoArr
                );
            }else{
                $line[$i]['price'] = preg_split('/(\d{1,2},\d{2})/', trim($part," ()"), 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            }
        }
    }
    unset($arr[$day]);
    $arr[date('l jS \of F Y h:i:s A',strtotime('Last Monday +'.array_search($day, $days).'days'))] = $line;
    
}
if(isset($_GET['format']) && $_GET['format']=='json'){
    echo json_encode($arr);
}else{
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
}
