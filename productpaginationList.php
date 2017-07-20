<?php

include './functions.php';

function selectUser($search, $orderById = 'pd_id', $orderBy = 'desc', $limit = '10', $offset) {
    $searchString = '';
    if (isset($search) && $search != '') {
        $searchString = "AND (pd_name LIKE '%$search%')";
    }
    $data = [];
    $result = [];
    $selectUser = "SELECT *  FROM product_details WHERE 1=1 $searchString ORDER BY $orderById $orderBy LIMIT $limit offset $offset ";
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
    $data = [];
    $selectUser = "SELECT *  FROM product_details";
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
$orderById = 'pd_id';
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
    if(isset($row['pd_price']) && $row['pd_price'] != ''){
        $result['result'][$u]['pd_price'] = "$ ".$row['pd_price'];
    }
    $result['result'][$u]['action'] = '<a href="javascript:;" onclick="deleteUser(' . $row['pd_id'] . ',' . $row['pd_refer_id'] . ')" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash-o"></i></a>&nbsp;&nbsp;<a href="editproduct.php?pd_id=' . $row['pd_id'] . '"  class="btn btn-default btn-sm" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;<a href="view_product.php?pd_id=' . $row['pd_id'] . '"  class="btn btn-default btn-sm" title="View Product"><i class="fa fa-eye"></i></a>';
    $u++;
}
$result['limit'] = $limit;
$result['orderById'] = $orderById;
$result['orderBy'] = $orderBy;
$result['searchText'] = $searchText;
$result['draw'] = isset($_REQUEST['draw']) ? $_REQUEST['draw'] : 0;
$response = array('draw' => $result['draw'], 'recordsTotal' => $result['totalRecord'], 'recordsFiltered' => $result['totalRecord'], 'data' => $result['result']);
echo json_encode($response);
