<?php

include 'db.php';
error_reporting(0);
/* http://localhost/Ebay_clone/web-api/edit_service.php?id=5&servicename=rajesh */

if ( isset($_REQUEST['servicename'])) {
    $servicename = $_REQUEST['servicename']; 
    $id = $_REQUEST['id'];
    
    $modified = date('Y-m-d H:i:s');
    
    $select = "SELECT * FROM service WHERE s_id = '$id'";
    $query = mysql_query($select);
    $row = mysql_fetch_assoc($query);
    if (!empty($row)) {
        $selectEmail = "SELECT * FROM service WHERE s_name = '$servicename'";
        $queryEmail = mysql_query($selectEmail);
        $rowEmail = mysql_fetch_assoc($queryEmail);
        if (!empty($rowEmail)) {
            $msg['status'] = "0";
            $msg['message'] = "Service Name Already Exits";
        } else {
           
                $qry1 = "UPDATE service SET s_name='$servicename', s_modified='$modified' WHERE s_id='" . $id . "'";            
            $res_select = mysql_query($qry1);
            if ($res_select) {
                $msg['status'] = "1";
                $msg['userid'] = $id;
                $msg['message'] = "Service Name Updated successfully";
            } else {
                $msg['status'] = "0";
                $msg['message'] = "Can't Updated";
            }
        }
    } else {
        $msg['status'] = "0";
        $msg['message'] = "User Not Found";
    }
}else{
    $msg['message'] = "wrong or missing parameters!";
}

$ar['status'] = $msg;
print json_encode($ar);

?>