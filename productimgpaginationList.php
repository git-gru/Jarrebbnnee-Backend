<?php

include './functions.php';

function selectUser($search, $orderById = 'pir_id', $orderBy = 'desc', $limit = '10', $offset) {
    $searchString = '';
//    p($_GET['product_ref_id']);
    if (isset($_GET['product_ref_id']) && $_GET['product_ref_id'] != '') {
        $product_ref_id = $_GET['product_ref_id'];
    }
    if (isset($search) && $search != '') {
        $searchString = "AND (pir_image LIKE '%$search%')";
    }
    $data = [];
    $result = [];
    $selectUser = "SELECT *  FROM product_img_rel WHERE pir_product_id=$product_ref_id $searchString ORDER BY $orderById $orderBy LIMIT $limit offset $offset ";
//    p($selectUser);
    $query = mysql_query($selectUser);
    $totalRecord = totalUser();
    $i = 0;
    $k = $totalRecord - $offset;
    while ($row = mysql_fetch_assoc($query)) {
        $data[$i] = $row;
        $i++;
    }
    $result['totalRecord'] = $totalRecord;
    $result['result'] = $data;
    return $result;
}

function totalUser() {
    if (isset($_GET['product_ref_id']) && $_GET['product_ref_id'] != '') {
        $product_ref_id = $_GET['product_ref_id'];
    }
    $data = [];
    $selectUser = "SELECT *  FROM product_img_rel WHERE pir_product_id=$product_ref_id";
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
$orderById = 'pir_id';
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
    if (isset($row['pir_image']) && $row['pir_image'] != '') {
        $imgPath = 'uploads/products/' . $row['pir_image'];
        $result['result'][$u]['pir_image'] = '<img src="' . $imgPath . '" style="width:75px;">';
    }
    $result['result'][$u]['action'] = '<a href="javascript:;" onclick="deleteUser(' . $row['pir_id'] . ')" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash-o"></i></a>';
    $u++;
}
$result['limit'] = $limit;
$result['orderById'] = $orderById;
$result['orderBy'] = $orderBy;
$result['searchText'] = $searchText;
$result['draw'] = isset($_REQUEST['draw']) ? $_REQUEST['draw'] : 0;
$response = array('draw' => $result['draw'], 'recordsTotal' => $result['totalRecord'], 'recordsFiltered' => $result['totalRecord'], 'data' => $result['result']);
echo json_encode($response);
