<?php

include './functions.php';

function selectUser($search, $orderById = 'ld_id', $orderBy = 'desc', $limit = '10', $offset) {

    if ($orderById == 'srno') {
        $orderById = 'ld_id';
    }

    $searchString = '';

    if (isset($search) && $search != '') {
        $searchString = "AND (ld_description LIKE '%$search%' OR ld_price LIKE '%$search%' OR ld_name LIKE '%$search%')";
    }

    $data = [];

    $result = [];

    $selectUser = "SELECT *  FROM lottery_details WHERE 1=1 $searchString ORDER BY $orderById $orderBy LIMIT $limit offset $offset ";

    $query = mysql_query($selectUser);
    $totalRecord = totallottery();
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

function totallottery() {

    $data = [];

    $selectUser = "SELECT *  FROM lottery_details";

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

$orderById = 'ld_id';

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

    if (isset($row['ld_image']) && $row['ld_image'] != '') {
        $result['result'][$u]['ld_image'] = '<img src="uploads/lottery/' . $row['ld_image'] . '" width="50" height="50">';
    }
    if (isset($row['ld_status']) && $row['ld_status'] == '0') {
        $newType = '1';
        $result['result'][$u]['ld_status'] = '<a href="javascript:;"  onclick="changeStatus(' . $row['ld_id'] . ',' . $newType . ');" class="btn btn-success btn-sm" title="Change Type">Active</a>';
    } else if (isset($row['ld_status']) && $row['ld_status'] == '1') {
        $newType = '0';
        $result['result'][$u]['ld_status'] = '<a href="javascript:;"  onclick="changeStatus(' . $row['ld_id'] . ',' . $newType . ');" class="btn btn-danger btn-sm" title="Change Type">Deactive</a>';
    }
    $result['result'][$u]['action'] = '<a href="javascript:;" onclick="deleteLottery(' . $row['ld_id'] . ')" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash-o"></i></a>&nbsp;&nbsp;<a href="addlottery.php?ld_id=' . $row['ld_id'] . '"  class="btn btn-default btn-sm" title="Edit"><i class="fa fa-pencil"></i></a>';

    $u++;
}



$result['limit'] = $limit;



$result['orderById'] = $orderById;



$result['orderBy'] = $orderBy;



$result['searchText'] = $searchText;

$result['draw'] = isset($_REQUEST['draw']) ? $_REQUEST['draw'] : 0;



$response = array('draw' => $result['draw'], 'recordsTotal' => $result['totalRecord'], 'recordsFiltered' => $result['totalRecord'], 'data' => $result['result']);



echo json_encode($response);

