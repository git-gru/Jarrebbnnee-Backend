<?php

include './functions.php';

function selectUser($search, $orderById = 'u_id', $orderBy = 'desc', $limit = '10', $offset) {

    if ($orderById == 'srno') {
        $orderById = 'u_id';
    }

    $searchString = '';

    if (isset($search) && $search != '') {
        $searchString = "AND (u_first_name LIKE '%$search%' OR u_last_name  LIKE '%$search%' OR u_email LIKE '%$search%')";
    }

    $data = [];

    $result = [];

    $selectUser = "SELECT *  FROM user WHERE u_type='1' $searchString ORDER BY $orderById $orderBy LIMIT $limit offset $offset ";
    $query = mysql_query($selectUser);
    $totalRecord = totalUser();
    $i = 0;
    $k = $totalRecord - $offset;
    while ($row = mysql_fetch_assoc($query)) {
        $row['srno'] = $k;
        $k--;
        $data[$i] = $row;
        $i++;
    }



    $result['totalRecord'] = $totalRecord;

    $result['result'] = $data;

    return $result;
}

function totalUser() {

    $data = [];

    $selectUser = "SELECT *  FROM user WHERE u_type='1'";

    $query = mysql_query($selectUser);

    $i = 0;

    while ($row = mysql_fetch_assoc($query)) {

        $data[$i] = $row;

        $i++;
    }

    return count($data);
}

if (strrpos($_SERVER["REQUEST_URI"], '?')) {

    $subStr = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], '?') + 1);

    parse_str($subStr, $_REQUEST);
}

$searchText = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value'] : '';



$limit = isset($_REQUEST['length']) ? $_REQUEST['length'] : 5;

$orderById = 'u_id';

$orderBy = 'asc';

if (isset($_REQUEST['order'])) {

    for ($i = 0, $ien = count($_REQUEST['order']); $i < $ien; $i++) {

        $columnIdx = intval($_REQUEST['order'][$i]['column']);

        $requestColumn = $_REQUEST['columns'][$columnIdx];

        $orderById = isset($requestColumn['data']) ? $requestColumn['data'] : 'u_id';

        $orderBy = 'DESC';

        if ($requestColumn['orderable'] == 'true') {

            $orderBy = $_REQUEST['order'][$i]['dir'] === 'asc' ?
                    'ASC' :
                    'DESC';
        }
    }
}

if (isset($_REQUEST['start'])) {

    $page = ($_REQUEST['start'] / $limit) + 1;

    $_GET['page'] = $page;
}



$result = selectUser($searchText, $orderById, $orderBy, $limit, $_REQUEST['start']);


$u = 0;

foreach ($result['result'] as $row) {
//    p($row);
    if (isset($row['user_photo']) && $row['user_photo'] != '') {
        $result['result'][$u]['user_photo'] = '<img src="uploads/userprofile/' . $row['user_photo'] . '" width="80" height="80">';
    }


    if (isset($row['u_status']) && $row['u_status'] == '0') {
        $newType = '1';
        $result['result'][$u]['u_status'] = '<a href="javascript:;"  onclick="changeStatus(' . $row['u_id'] . ',' . $newType . ');" class="btn btn-success btn-sm" title="Change Type">Active</a>';
    } else if (isset($row['u_status']) && $row['u_status'] == '1') {
        $newType = '0';
        $result['result'][$u]['u_status'] = '<a href="javascript:;"  onclick="changeStatus(' . $row['u_id'] . ',' . $newType . ');" class="btn btn-danger btn-sm" title="Change Type">Block</a>';
    }

    if (isset($row['u_created']) && $row['u_created'] != '') {
        $result['result'][$u]['u_created'] = date('d-m-Y', strtotime($row['u_created']));
    }
    $action = '';
    $action.= '<a href="javascript:;" onclick="deleteUser(' . $row['u_id'] . ')" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash-o"></i></a>&nbsp;&nbsp;<a href="adduser.php?u_id=' . $row['u_id'] . '"  class="btn btn-default btn-sm" title="Edit"><i class="fa fa-pencil"></i></a>';
    $result['result'][$u]['action'] = $action;
    $u++;
}



$result['limit'] = $limit;



$result['orderById'] = $orderById;



$result['orderBy'] = $orderBy;



$result['searchText'] = $searchText;

$result['draw'] = isset($_REQUEST['draw']) ? $_REQUEST['draw'] : 0;



$response = array('draw' => $result['draw'], 'recordsTotal' => $result['totalRecord'], 'recordsFiltered' => $result['totalRecord'], 'data' => $result['result']);



echo json_encode($response);

