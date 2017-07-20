<?php

error_reporting(0);
require 'general.php';
$commonObj = new General();

$conn = mysql_connect('localhost', 'shubante_jarrebb', '7{^@NP-d]vo&') or die("Can not connect." . mysql_error());
$db = mysql_select_db('shubante_jarrebbnnee') or die("Can not connect." . mysql_error());

function p($data, $exit = 1) {
    echo "<pre>";
    print_r($data);
    if ($exit == 1) {
        exit;
    }
}

if (!function_exists('serviceCateDropdown')) {

    function serviceCateDropdown($selected = '', $action = '') {
        $role = [];
        $select = "SELECT * FROM category WHERE c_type = '0'";
        $query = mysql_query($select);
        $i = 0;
        while ($row = mysql_fetch_assoc($query)) {
            $role[$i] = $row;
            $i++;
        }
        $htmlString = '<option value="0">Select Category</option>';
        foreach ($role as $row) {
            if ($selected == $row['c_id']) {
                $select = 'selected';
                $htmlString.="<option " . $select . " value='" . $row['c_id'] . "'>" . $row['c_title'] . "</option>";
            } else {
                $htmlString.="<option value='" . $row['c_id'] . "'>" . $row['c_title'] . "</option>";
            }
        }
        $dropDown = "<select id='c_title' " . $action . " name='c_title' data-constraints='@Required' class='form-control bg-white input-xlarge'>" . $htmlString . "</select>";
        return $dropDown;
    }

}

if (!function_exists('advtServiceDropdown')) {
    function advtServiceDropdown($selected = '', $cateId = '') {
        $role = [];
        $select = "SELECT * FROM service WHERE s_category_id = '" . $cateId . "'";
        $query = mysql_query($select);
        $i = 0;
        while ($row = mysql_fetch_assoc($query)) {
            $role[$i] = $row;
            $i++;
        }
        $htmlString = '<option value="0">Select Service</option>';
        foreach ($role as $row) {
            if ($selected == $row['s_id']) {
                $select = 'selected';
                $htmlString.="<option " . $select . " value='" . $row['s_id'] . "'>" . $row['s_title'] . "</option>";
            } else {
                $htmlString.="<option value='" . $row['s_id'] . "'>" . $row['s_title'] . "</option>";
            }
        }
        $dropDown = "<select id='s_title' name='s_title' data-constraints='@Required' class='form-control bg-white input-xlarge'>" . $htmlString . "</select>";
        return $dropDown;
    }

}
if (!function_exists('serviceDropdown')) {

    function serviceDropdown($selected = '') {
        $role = [];
        $select = "SELECT * FROM service";
        $query = mysql_query($select);
        $i = 0;
        while ($row = mysql_fetch_assoc($query)) {
            $role[$i] = $row;
            $i++;
        }
        $htmlString = '<option value="0">Select Service</option>';
        foreach ($role as $row) {
            if ($selected == $row['s_id']) {
                $select = 'selected';
                $htmlString.="<option " . $select . " value='" . $row['s_id'] . "'>" . $row['s_title'] . "</option>";
            } else {
                $htmlString.="<option value='" . $row['s_id'] . "'>" . $row['s_title'] . "</option>";
            }
        }
        $dropDown = "<select id='s_title' name='s_title' data-constraints='@Required' class='form-control bg-white input-xlarge'>" . $htmlString . "</select>";
        return $dropDown;
    }

}
if (!function_exists('mainserviceDropdown')) {

    function mainserviceDropdown($selected = '') {
        $vendor = [];
        $select = "SELECT * FROM service where s_type='0'";
        $query = mysql_query($select);
        $i = 0;
        while ($row = mysql_fetch_assoc($query)) {
            $vendor[$i] = $row;
            $i++;
        }
        $htmlString = '<option value="0">Select main Service</option>';
        foreach ($vendor as $row) {
            if ($selected == $row['s_id']) {
                $select = 'selected';
                $htmlString.="<option " . $select . " value='" . $row['s_id'] . "'>" . $row['s_name'] . "</option>";
            } else {
                $htmlString.="<option value='" . $row['s_id'] . "'>" . $row['s_name'] . "</option>";
            }
        }
        $dropDown = "<select name='service_name' id='s_id' class='form-control'>" . $htmlString . "</select>";
        return $dropDown;
    }

}
if (!function_exists('countryDropdown')) {

    function countryDropdown($selected = '',$action = '') {
        $regions = [];
        $select = "SELECT * FROM country_code";
        $query = mysql_query($select);
        $i = 0;
        while ($row = mysql_fetch_assoc($query)) {
            $regions[$i] = $row;
            $i++;
        }
        $htmlString = '<option value="0">Country Name</option>';
        foreach ($regions as $row) {
            if ($selected == $row['id']) {
                $select = 'selected';
                $htmlString.="<option " . $select . " value='" . $row['id'] . "'>" . $row['country'] . "(+" . $row['phone_prefix'] . ")" . "</option>";
            } else {
                $htmlString.="<option value='" . $row['id'] . "'>" . $row['country'] . "(+" . $row['phone_prefix'] . ")" . "</option>";
            }
        }
        $dropDown = "<select name='phone_prefix' ".$action." id='country_code' class='form-control input-xlarge'>" . $htmlString . "</select>";
        return $dropDown;
    }

}
if (!function_exists('cityDropdown')) {
    function cityDropdown($selected = '', $cityId = '') {
        $role = [];
//        p($cityId);
        $select = "SELECT * FROM city WHERE city_country_id = '" . $cityId . "'";
        $query = mysql_query($select);
        $i = 0;
        while ($row = mysql_fetch_assoc($query)) {
            $role[$i] = $row;
            $i++;
        }
        $htmlString = '<option value="0">Select City</option>';
        foreach ($role as $row) {
            if ($selected == $row['city_id']) {
                $select = 'selected';
                $htmlString.="<option " . $select . " value='" . $row['city_id'] . "'>" . $row['city_name'] . "</option>";
            } else {
                $htmlString.="<option value='" . $row['city_id'] . "'>" . $row['city_name'] . "</option>";
            }
        }
        $dropDown = "<select id='city_name' name='city_name' data-constraints='@Required' class='form-control bg-white input-xlarge'>" . $htmlString . "</select>";
        return $dropDown;
    }

}
?>