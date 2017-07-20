<?php

require_once './v1/controller.php';
$publicFunArray = ['userLogin','userRagister','socialLogin','countryList','userProfile','getCategoryList','getProductList','getProductDetail',
    'getFilterResult','getHomeSlider','addWishList','getWishList','deleteWishList','addComment','deleteComment','getCommentList',
    'addToCart','getMyCart','deleteFromCart','addToOrder','updateQuntityCartItem','ragisterDevice','changePassword','forgotPassword',
    'addMyAddressBook','deleteMyAddressbook','getMyAddressBook','applyCoupon'
    ];
//$privateFunArray = ['user_profile'];
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array
if (isset($input) && is_array($input) && !empty($input)) {
    $_REQUEST = $input;
    $_POST = $input;
} else {
    $_POST = $_REQUEST;
}

if (!isset($_REQUEST['action']) || !in_array($_REQUEST['action'], $publicFunArray)) {
    echo json_encode(array("status" => 0, "message" => "method not exist"));
    exit;
}

if (in_array($_REQUEST['action'], $privateFunArray)) {
    if (isset($_REQUEST['u_id']) && $_REQUEST['u_id'] != '') {
        
    } else {
        echo json_encode(array(['status' => '0', 'message' => 'Invalid user found']));
        exit;
    }
}


$methodName = $_REQUEST['action'];
$message = date("Y-m-d h:i:s") . " : Called WS:" . $methodName;

$_POST['api_call'] = 1;
$controllerObj = new Controller();
$result = $controllerObj->$methodName();
echo json_encode($result);
error_log($message, 3, "logs.log");
error_log("/n", 3, "logs.log");
error_log(print_r($_REQUEST, true), 3, "logs.log");
exit;
?>