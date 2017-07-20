<?php

require_once './functions.php';
error_reporting(E_ALL);
if (isset($_GET['action']) && $_GET['action'] != '') {
    $action = $_GET['action'];
    switch ($action) {
        case 'getServicename':
            $result = advtServiceDropdown('', $_GET['cate_id']);
            echo json_encode($result);
            break;
        case 'getCityname':
            $result = cityDropdown('', $_GET['city_id']);
//            p($result);
            echo json_encode($result);
            break;
         case 'saveProductImport':
            $msg = 'Product imported success';
            $result = saveProductImport();
            if (isset($result['status']) && $result['status'] == '0') {
                $msg = 'Product imported success';
                header('location:product_list.php?type=s&msg=' . $msg);
            } else {
                $msg = 'Please select csv of prodct details';
                header('location:product_list.php?type=e&msg=' . $msg);
            }
            exit;
            break;
        case 'saveProductImportImg':
            $msg = 'Product imported success';
            $result = saveProductImportImg();
            if (isset($result['status']) && $result['status'] == '0') {
                $msg = 'Product imported success';
                header('location:product_list.php?type=s&msg=' . $msg);
                exit;
            } else {
                $msg = 'Please select csv of images';
                header('location:product_list.php?type=e&msg=' . $msg);
                exit;
            }


            break;
        case 'saveProductImport':
            $msg = 'Product imported success';
            $result = saveProductImport();
            if (isset($result['status']) && $result['status'] == '0') {
                $msg = 'Product imported success';
                header('location:product_list.php?type=s&msg=' . $msg);
            } else {
                $msg = 'Please select csv of prodct details';
                header('location:product_list.php?type=e&msg=' . $msg);
            }
            exit;
            break;
        case 'saveProductImportImg':
            $msg = 'Product imported success';
            $result = saveProductImportImg();
            if (isset($result['status']) && $result['status'] == '0') {
                $msg = 'Product imported success';
                header('location:product_list.php?type=s&msg=' . $msg);
                exit;
            } else {
                $msg = 'Please select csv of images';
                header('location:product_list.php?type=e&msg=' . $msg);
                exit;
            }


            break;
    }
} else {
    header('location: index.php');
}
?>