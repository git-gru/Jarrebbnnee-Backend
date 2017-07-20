<?php

require_once 'modal.php';

Class Controller {

    function deviceValidate() {
        if (!isset($_POST['device_token']) || $_POST['device_token'] == '') {
            return array("status" => 0, "message" => "device token not found.");
        } else {
            
        }
    }

    function deviceragister() {
        $result = ragisterDevice($_REQUEST);
        return $result;
    }

    function loginAdmin() {
        $result = $this->deviceValidate();
        $result = loginAdmin($_REQUEST);
        return $result;
    }
    
    function countryList() {
        $result = $this->deviceValidate();
        $result = countryList($_REQUEST);
        return $result;
    }

    function userRagister() {
        $result = $this->deviceValidate();
        $result = userRagister($_REQUEST);
        return $result;
    }
    
    function userLogin() {
        $result = $this->deviceValidate();
        $result = userLogin($_REQUEST);
        return $result;
    }
    
     function socialLogin() {
        $result = $this->deviceValidate();
        $result = socialLogin($_REQUEST);
        return $result;
    }
    
    function userProfile() {
        $result = $this->deviceValidate();
        $result = userProfile($_REQUEST);
        return $result;
    }
    function getCategoryList() {
        $result = $this->deviceValidate();
        $result = GetCategoryList($_REQUEST);
        return $result;
    }
    function getProductList() {
        $result = $this->deviceValidate();
        $result = getProductList($_REQUEST);
        return $result;
    }
    function getProductDetail() {
        $result = $this->deviceValidate();
        $result = getProductDetail($_REQUEST);
        return $result;
    }
    function getFilterResult() {
        $result = $this->deviceValidate();
        $result = getFilterResult($_REQUEST);
        return $result;
    }
    function getHomeSlider() {
        $result = $this->deviceValidate();
        $result = getHomeSlider($_REQUEST);
        return $result;
    }
    
    function addWishList() {
        $result = $this->deviceValidate();
        $result = addWishList($_REQUEST);
        return $result;
    }
    function getWishList() {
        $result = $this->deviceValidate();
        $result = getWishList($_REQUEST);
        return $result;
    }
    function deleteWishList() {
        $result = $this->deviceValidate();
        $result = deleteWishList($_REQUEST);
        return $result;
    }
    function addComment() {
        $result = $this->deviceValidate();
        $result = addComment($_REQUEST);
        return $result;
    }
    function deleteComment() {
        $result = $this->deviceValidate();
        $result = deleteComment($_REQUEST);
        return $result;
    }
    function getCommentList() {
        $result = $this->deviceValidate();
        $result = getCommentList($_REQUEST);
        return $result;
    }
    function addToCart() {
        $result = $this->deviceValidate();
        $result = addToCart($_REQUEST);
        return $result;
    }
    function getMyCart() {
        $result = $this->deviceValidate();
        $result = getMyCart($_REQUEST);
        return $result;
    }
    function deleteFromCart() {
        $result = $this->deviceValidate();
        $result = deleteFromCart($_REQUEST);
        return $result;
    }
    function updateQuntityCartItem() {
        $result = $this->deviceValidate();
        $result = updateQuntityCartItem($_REQUEST);
        return $result;
    }
    function addToOrder() {
        $result = $this->deviceValidate();
        $result = addToOrder($_REQUEST);
        return $result;
    }
    function changePassword() {
        $result = $this->deviceValidate();
        $result = changePassword($_REQUEST);
        return $result;
    }
     function forgotPassword() {
        $result = $this->deviceValidate();
        $result = forgotPassword($_REQUEST);
        return $result;
    }
    function applyCoupon() {
        $result = $this->deviceValidate();
        $result = applyCoupon($_REQUEST);
        return $result;
    }
    function addMyAddressBook() {
        $result = $this->deviceValidate();
        $result = addMyAddressBook($_REQUEST);
        return $result;
    }
    function deleteFromAddressBook() {
        $result = $this->deviceValidate();
        $result = deleteFromAddressBook($_REQUEST);
        return $result;
    }
    function updateMyAddressbook() {
        $result = $this->deviceValidate();
        $result = updateMyAddressbook($_REQUEST);
        return $result;
    }
    function getMyAddressBook() {
        $result = $this->deviceValidate();
        $result = getMyAddressBook($_REQUEST);
        return $result;
    }
    function placeOrder() {
        $result = $this->deviceValidate();
        $result = placeOrder($_REQUEST);
        return $result;
    }
}

?>