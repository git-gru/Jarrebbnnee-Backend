<?php

include 'db.php';
error_reporting(0);
/* http://localhost/Ebay_clone/web-api/add_service.php?servicename=rajesh&images= */

if (isset($_REQUEST['servicename']) && isset($_REQUEST['images'])) {
    $servicename = $_REQUEST['servicename'];
    $image = $_REQUEST['images'];
    $status = "0";
    $created = date('Y-m-d H:i:s');




    if ($image != '') {
//            echo  "abc";
        $img1 = rand(99999, 9999999);
        base64_to_jpeg('data:image/' . $type . ';base64,' . $image, 'A_' . $img1 . '.jpg');
        $image_name .= 'A_' . $img1 . '.jpg';
    } else {
        $image_name = '';
    }

    $qry_select = "select * from service where s_name ='" . $servicename . "'";
    if ($qry_select) {
        $res_select = mysql_query($qry_select);
        if (mysql_num_rows($res_select) > 0) {
            $row = mysql_fetch_array($res_select);
            //print_r($row);
            $msg['userid'] = $row['u_id'];
            $msg['message'] = "Already registred with this service name";
        } else {

            $query = "INSERT INTO `service`(`s_name`,`s_img`,`s_status`,`s_create`)
                    VALUES ('$servicename','$image_name','$status','$created')";

            $result = mysql_query($query);
            if ($result) {
                $id = mysql_insert_id();
                $msg['status'] = "1";
                $msg['userid'] = $id;
                $msg['message'] = "Service Successfully Added";
            } else {
                $msg['userid'] = "0";
                $msg['message'] = mysql_error();
            }
        }
    } else {
        $msg['userid'] = "0";
        $msg['message'] = mysql_error();
    }
} else {
    $msg['message'] = "wrong or missing parameters!";
}

$ar['status'] = $msg;
print json_encode($ar);

function base64_to_jpeg($base64_string, $output_file) {
    $ifp = fopen('../uploads/serviceprofile/' . $output_file, "wb");
    $data = explode(',', $base64_string);
    fwrite($ifp, base64_decode($data[1]));
    fclose($ifp);
    return $output_file;
}
?>