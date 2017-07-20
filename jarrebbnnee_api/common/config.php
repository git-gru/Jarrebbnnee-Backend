<?php
define('SITEURL','http://pr.veba.co/~shubantech/jarrebbnnee/');
define('DEFAULT_LANGUAGE','eng');//arabic

if(isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST']=='localhost'){
$conn = mysqli_connect('localhost', 'root', '','jarrebbnnee') or die(mysql_error());
}  else {
    $conn = mysqli_connect('localhost', 'shubante_jarrebb', '7{^@NP-d]vo&','shubante_jarrebbnnee') or die("Can not connect." . mysql_error());
}

//mysql_select_db('jarrebbnnee') or die(mysql_error());

function p($data, $exit = 1) {
    echo "<pre>";
    print_r($data);
    if ($exit == 1) {
        exit();
    }
}


