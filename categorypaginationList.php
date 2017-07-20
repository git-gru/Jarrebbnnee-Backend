<?php

include './functions.php';

function selectUser($search, $orderById = 'pc_id', $orderBy = 'desc', $limit = '10', $offset) {

    if ($orderById == 'srno') {
        $orderById = 'pc_id';
    }

    $searchString = '';

    if (isset($search) && $search != '') {
        $searchString = "AND (c_title LIKE '%$search%')";
    }

    $data = [];

    $result = [];

    $selectUser = "SELECT *  FROM product_category WHERE 1=1 $searchString ORDER BY $orderById $orderBy LIMIT $limit offset $offset ";

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

    $selectUser = "SELECT *  FROM product_category";

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

$orderById = 'pc_id';

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

//    if (isset($row['c_images']) && $row['c_images'] != '') {
//         $result['result'][$u]['c_images'] = '<img src="uploads/serviceprofile/' . $row['c_images'] . '" width="80" height="80">';
//     }
//    
//    if (isset($row['s_status']) && $row['s_status'] == '0') {
//        $newType = '1';
//        $result['result'][$u]['s_status'] = '<a href="javascript:;"  onclick="changeStatus(' . $row['s_id'] . ',' . $newType . ');" class="btn btn-success btn-sm" title="Change Type">Active</a>';
//    } else if (isset($row['s_status']) && $row['s_status'] == '1') {
//        $newType = '0';
//        $result['result'][$u]['s_status'] = '<a href="javascript:;"  onclick="changeStatus(' . $row['s_id'] . ',' . $newType . ');" class="btn btn-danger btn-sm" title="Change Type">Block</a>';
//    }
    
//    if(isset($row['c_is_parent_id']) && $row['c_is_parent_id'] == 0){
//        $result['result'][$u]['c_is_parent_id'] = '';
//    }else{
//        $select = "SELECT * FROM product_category WHERE pc_id = '".$row['c_is_parent_id']."'";
//        $query = mysql_query($select);
//        $rowC = mysql_fetch_assoc($query);
//        $result['result'][$u]['c_is_parent_id'] = $rowC['c_title'];
//    }
//    if (isset($row['s_create']) && $row['s_create'] != '') {
//        $result['result'][$u]['s_create'] = date('d-m-Y', strtotime($row['s_create']));
//    }

    $result['result'][$u]['action'] = '<a href="javascript:;" onclick="deleteUser(' . $row['pc_id'] . ')" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash-o"></i></a>&nbsp;&nbsp;<a href="addcategory.php?pc_id=' . $row['pc_id'] . '"  class="btn btn-default btn-sm" title="Edit"><i class="fa fa-pencil"></i></a>';

    $u++;
}



$result['limit'] = $limit;



$result['orderById'] = $orderById;



$result['orderBy'] = $orderBy;



$result['searchText'] = $searchText;

$result['draw'] = isset($_REQUEST['draw']) ? $_REQUEST['draw'] : 0;



$response = array('draw' => $result['draw'], 'recordsTotal' => $result['totalRecord'], 'recordsFiltered' => $result['totalRecord'], 'data' => $result['result']);



echo json_encode($response);

