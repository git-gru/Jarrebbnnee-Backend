<?php

include 'common/config.php';

function generateRandomString($length = 10) {
    global $conn;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function insert($tableName, $data) {
    global $conn;
    $fields = '';
    $values = '';
    end($data);
    $lastElement = key($data);
    foreach ($data as $key => $value) {
        if ($lastElement == $key) {
            $fields.=$key;
            $values.="'$value'";
        } else {
            $fields.=$key . ',';
            $values.="'$value',";
        }
    }

    $checkFields = checkTableFields($tableName, $data);
    if (isset($checkFields['status']) && $checkFields['status'] == 0) {

        $insert = "INSERT INTO `" . $tableName . "` ($fields) VALUES($values)";
        mysqli_query($conn, $insert);
        return ['status' => 0, 'message' => 'record inserted', 'ID' => mysqli_insert_id($conn)];
    } else {
        return $checkFields;
    }
}

function update($tableName, $data, $params) {
    global $conn;
    $updateString = '';
    $condition = '';
    end($data);
    $lastElement = key($data);
    end($params);
    $lastParams = key($params);
    foreach ($data as $key => $value) {
        if ($lastElement == $key) {
            $updateString.="$key = '$value'";
        } else {
            $updateString.="$key = '$value' , ";
        }
    }

    foreach ($params as $key => $value) {
        if ($lastParams == $key) {
            $condition.="$key = '$value'";
        } else {
            $condition.="$key = '$value' AND ";
        }
    }

    $checkFields = checkTableFields($tableName, $data);
    if (isset($checkFields['status']) && $checkFields['status'] == 0) {
        $select = "SELECT * FROM $tableName WHERE $condition";
        $selectQuery = mysqli_query($conn, $select);
        $row = mysqli_fetch_assoc($selectQuery);
        if (!empty($row)) {
            $update = "UPDATE $tableName SET $updateString WHERE $condition";
            mysqli_query($conn, $update);
            return['status' => 0, 'message' => 'record updated'];
        } else {
            return['status' => 1, 'message' => 'invalid params'];
        }
    } else {
        return $checkFields;
    }
}

function queryOne($tableName, $params) {
    global $conn;
    $checkFields = checkTableFields($tableName, $params);
    if (isset($checkFields['status']) && $checkFields['status'] == 0) {
        end($params);
        $lastParams = key($params);
        $condition = '';
        foreach ($params as $key => $value) {
            if ($lastParams == $key) {
                $condition.="$key = '$value'";
            } else {
                $condition.="$key = '$value' AND ";
            }
        }

        $select = "SELECT * FROM $tableName WHERE $condition";
        // p($select );
        $selectQuery = mysqli_query($conn, $select);
        return mysqli_fetch_assoc($selectQuery);
    } else {
        return $checkFields;
    }
}

function delete($tableName, $params) {
    global $conn;
    $checkFields = checkTableFields($tableName, $params);
    if (isset($checkFields['status']) && $checkFields['status'] == 0) {
        end($params);
        $lastParams = key($params);
        $condition = '';
        foreach ($params as $key => $value) {
            if ($lastParams == $key) {
                $condition.="$key = '$value'";
            } else {
                $condition.="$key = '$value' AND ";
            }
        }

        $select = "DELETE FROM $tableName WHERE $condition";
        $selectQuery = mysqli_query($conn, $select);
        return ['status' => 1];
    } else {
        return $checkFields;
    }
}

function queryAll($tableName, $params = '') {
    global $conn;
    $lastParams = '';
    $condition = '';
    if (!empty($params)) {
        $checkFields = checkTableFields($tableName, $params);
        end($params);
        $lastParams = key($params);

        foreach ($params as $key => $value) {
            if ($lastParams == $key) {
                $condition.="AND $key = '$value'";
            } else {
                $condition.="AND $key = '$value'";
            }
        }
    } else {
        $checkFields['status'] = 0;
    }
    $data1 = [];
    if (isset($checkFields['status']) && $checkFields['status'] == 0) {
        $select = "SELECT * FROM $tableName WHERE 1=1 $condition";
        $selectQuery = mysqli_query($conn, $select);
        $i = 0;
        while ($row = mysqli_fetch_assoc($selectQuery)) {
            $data1[$i] = $row;
            $i++;
        }
        return $data1;
    }
}

function checkTableFields($tableName, $data) {
    global $conn;
    $getColumnString = "SHOW COLUMNS FROM `" . $tableName . "`";
    $queryColumn = mysqli_query($conn, $getColumnString);
    $fields = [];
    $i = 0;
    while ($rowColumn = mysqli_fetch_assoc($queryColumn)) {
        $fields[$i] = $rowColumn;
        $i++;
    }
    $exactFields = [];
    foreach ($fields as $key => $value) {
        if (!in_array($value['Field'], $exactFields)) {
            $exactFields[] = $value['Field'];
        }
    }
    $fieldFlag = 0;
    foreach ($data as $key => $value) {
        if (in_array($key, $exactFields)) {
            $fieldFlag = 1;
        } else {
            return ['status' => 1, 'message' => 'Unknown filed ' . $key];
        }
    }
    if ($fieldFlag) {
        return ['status' => 0];
    }
}

//common function END here 

function fieldPurify($data = "") {
    global $conn;
    if ($data == '') {
        return $data;
    }
    $data = str_replace("'", "", $data);
    $data = str_replace('"', "", $data);
    return $data;
}

function sendMail($to, $subject, $message) {
    global $conn;
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
    mail($to, $subject, $message, $headers);
    return true;
}

function getByFieldName($tableName, $fieldName, $fieldValue) {
    global $conn;
    $tableData = [];
    $params = [
        $fieldName => $fieldValue
    ];
    $tableData = queryOne($tableName, $params);
    return $tableData;
}

function loginAdmin() {
    global $conn;
    $result = ragisterDevice($_REQUEST);
    if (isset($result['status']) && $result['status'] == 0) {
        if (isset($_REQUEST['email']) && $_REQUEST['email'] != '') {
            $email = $_REQUEST['email'];
        } else {
            return ['status' => 1, 'message' => 'Please enter email address.'];
        }

        if (isset($_REQUEST['password']) && $_REQUEST['password'] != '') {
            $password = $_REQUEST['password'];
        } else {
            return ['status' => 1, 'message' => 'Please enter your password.'];
        }

        $checkByEmail = getByFieldName('admin', 'email', $email);
        if (!empty($checkByEmail)) {
            if (isset($checkByEmail['password']) && $checkByEmail['password'] != '') {
                if ($checkByEmail['password'] == md5($password)) {
                    $_REQUEST['user_id'] = $checkByEmail['id'];
                    ragisterDevice($_REQUEST);
                    return ['status' => 0, 'message' => 'Admin data', 'data' => $checkByEmail];
                } else {
                    return ['status' => 1, 'message' => 'Email and password is not match.'];
                }
            } else {
                return ['status' => 1, 'message' => 'User not ragister'];
            }
        } else {
            return ['status' => 1, 'message' => 'Invalid email address'];
        }
    } else {
        return $result;
    }
}

function countryList() {
    global $conn;
    $result = [];
    $i = 0;
    $id = $_REQUEST['country_id'];
    $select = "SELECT * FROM country_code";
    $query = mysqli_query($conn, $select);
    while ($row = mysqli_fetch_assoc($query)) {
        $result[$i] = $row;
        $i++;
    }
    if (empty($result))
        return ['status' => 1, 'message' => 'Record Not Found.'];
    return ['status' => 0, 'message' => 'Country List.', 'data' => $result];
}

function userRagister($socialFlag = 1) {
    global $conn;
    $result = ragisterDevice($_REQUEST);
    if ($socialFlag == 0) {
        $_REQUEST['u_password'] = generateRandomString(5);
    }
    if (!isset($result['status']) || $result['status'] != 0) {
        return $result;
    }
    if (isset($result['status']) && $result['status'] == 0) {
        if (isset($_REQUEST['u_email']) && $_REQUEST['u_email'] != '') {
            $insertData['u_email'] = $_REQUEST['u_email'];
        } else {
            return ['status' => 0, 'message' => 'Please enter email address.'];
        }

        if (isset($_REQUEST['u_password']) && $_REQUEST['u_password'] != '') {
            $insertData['u_password'] = md5($_REQUEST['u_password']);
        } else {
            return ['status' => 0, 'message' => 'Please enter your password.'];
        }

        if (isset($_REQUEST['u_first_name']) && $_REQUEST['u_first_name'] != '') {
            $insertData['u_first_name'] = $_REQUEST['u_first_name'];
        } else {
            return ['status' => 0, 'message' => 'Please enter your first name.'];
        }

        if (isset($_REQUEST['u_last_name']) && $_REQUEST['u_last_name'] != '') {
            $insertData['u_last_name'] = $_REQUEST['u_last_name'];
        } else {
            return ['status' => 0, 'message' => 'Please enter your last name.'];
        }

        if (isset($_REQUEST['u_country']) && $_REQUEST['u_country'] != '') {
            $insertData['u_country'] = $_REQUEST['u_country'];
        }

        if (isset($_REQUEST['u_address']) && $_REQUEST['u_address'] != '') {
            $insertData['u_address'] = $_REQUEST['u_address'];
        }
        if (isset($_REQUEST['u_phone']) && $_REQUEST['u_phone'] != '') {
            $insertData['u_phone'] = $_REQUEST['u_phone'];
        }

        $checkByEmail = getByFieldName('user', 'u_email', $_REQUEST['u_email']);

        if (!empty($checkByEmail)) {
            return ['status' => 1, 'message' => 'Email already register'];
        } else {
            $insertData['u_crteated'] = date('Y-m-d H:i:s');
            $result = insert('user', $insertData);
            if (isset($result['ID']) && $result['ID'] != '') {
                $_REQUEST['u_id'] = $result['ID'];
                ragisterDevice($_REQUEST);
                $params = [
                    'u_id' => $result['ID'],
                ];
                $userData = queryOne('user', $params);
                return ['status' => 0, 'message' => 'User ragister success', 'data' => $result];
            } else {
                return $result;
            }
        }
    }
}

function userLogin() {
    global $conn;
    $result = ragisterDevice($_REQUEST);
    if (isset($result['status']) && $result['status'] == 0) {
        if (isset($_REQUEST['u_email']) && $_REQUEST['u_email'] != '') {
            $email = $_REQUEST['u_email'];
        } else {
            return ['status' => 1, 'message' => 'Please enter email address.'];
        }

        if (isset($_REQUEST['u_password']) && $_REQUEST['u_password'] != '') {
            $password = $_REQUEST['u_password'];
        } else {
            return ['status' => 1, 'message' => 'Please enter your password.'];
        }

        $checkByEmail = getByFieldName('user', 'u_email', $email);

        if (!empty($checkByEmail)) {
            if (isset($checkByEmail['u_password']) && $checkByEmail['u_password'] != '') {
                if ($checkByEmail['u_password'] == md5($password)) {
                    $_REQUEST['user_id'] = $checkByEmail['u_id'];
                    ragisterDevice($_REQUEST);
                    return ['status' => 0, 'message' => 'user data', 'data' => $checkByEmail];
                } else {
                    return ['status' => 1, 'message' => 'Email and password is not match.'];
                }
            } else {
                return ['status' => 1, 'message' => 'User not ragister'];
            }
        } else {
            return ['status' => 1, 'message' => 'Invalid email address'];
        }
    } else {
        return $result;
    }
}

function socialLogin() {
    global $conn;
    $result = ragisterDevice($_REQUEST);
    if (isset($_REQUEST['sm_social_provider']) && $_REQUEST['sm_social_provider'] != '') {
        $social_provider_type = strtolower($_REQUEST['sm_social_provider']);
    } else {
        return ['status' => 1, 'message' => 'Undefined social Provider'];
    }

    if (isset($_REQUEST['sm_social_provider_id']) && $_REQUEST['sm_social_provider_id'] != '') {
        $sm_social_provider_id = strtolower($_REQUEST['sm_social_provider_id']);
    } else {
        return ['status' => 1, 'message' => 'Undefined social Provider id'];
    }

    $checkExistResult = checkExistSocial($sm_social_provider_id, $social_provider_type);
    //p($checkExistResult);        
    if (isset($checkExistResult['status']) && $checkExistResult['status'] == 0) {
        //from user table need to fetch by user id - Amrut.
        if (isset($checkExistResult['data']['sm_user_id']) && $checkExistResult['data']['sm_user_id'] != '') {
            $paramsUsers = [
                'u_id' => $checkExistResult['data']['sm_user_id']
            ];

            $userData = queryOne('user', $paramsUsers);
            if (!empty($userData)) {
                return ['status' => 0, 'message' => 'Login success', 'data' => $userData];
            } else {
                return ['status' => 1, 'messsage' => 'Your data is mismatch , Please contact at administrator.'];
            }
        } else {
            return ['status' => 1, 'message' => 'something want wrong please try again'];
        }
    } else {
        //need to insert new user ragister - Amrut.
        $ragisterData = userRagister(0);
        if (isset($ragisterData['status']) && $ragisterData['status'] == 0) {
            if (isset($ragisterData['data']['u_id']) && $ragisterData['data']['u_id'] != '') {
                $insertSm = insertSocialManage($ragisterData['data']['u_id'], $_REQUEST);
                if (isset($insertSm['ID']) && $insertSm['ID'] != '') {
                    //response is same as login - Amrut.
                    return $ragisterData;
                } else {
                    return ['status' => 1, 'message' => 'Your data is mismatch , Please contact at administrator.'];
                }
            } else {
                return ['status' => 1, 'message' => 'Your data is mismatch , Please contact at administrator.'];
            }
        } else {
            return $ragisterData;
        }
    }
}

function ragisterDevice($data) {
    global $conn;
    if (isset($data['device_type']) && $data['device_type'] != '') {
        $insertData['mr_device_type'] = $data['device_type'];
    } else {
        return ['status' => 1, 'message' => 'Invalid device type selection.Should be Android or iPhone'];
    }

    if (isset($data['device_token']) && $data['device_token'] != '') {
        $insertData['mr_device_token'] = $data['device_token'];
    } else {
        return ['status' => 1, 'message' => 'Invalid device token.'];
    }

    if (isset($data['user_id']) && $data['user_id'] != '') {
        $insertData['mr_user_id'] = $data['user_id'];
    }

    $params = [
        'mr_device_type' => $data['device_type'],
        'mr_device_token' => $data['device_token']
    ];

    $checkData = queryOne('mobile_ragister', $params);
    if (!empty($checkData)) {
        $insertData['mr_modified'] = date('Y-m-d H:i:s');
        update('mobile_ragister', $insertData, $params);
        return ['status' => 0, 'message' => 'Device validate success.'];
    } else {
        $insertData['mr_created'] = date('Y-m-d H:i:s');
        insert('mobile_ragister', $insertData);
        return ['status' => 0, 'message' => 'Device ragister sucess.'];
    }
}

function checkExistSocial($id, $type) {
    global $conn;
    $params = [
        'sm_social_provider_id' => $id,
        'sm_social_provider' => $type,
    ];

    $socialData = queryOne('social_manage', $params);
    if (!empty($socialData)) {
        return ['status' => 0, 'data' => $socialData];
    } else {
        return ['status' => 1];
    }
}

function insertSocialManage($userId, $socialData) {
    global $conn;
    $smData = [
        'sm_user_id' => $userId,
        'sm_social_provider_id' => $socialData['sm_social_provider_id'],
        'sm_social_provider' => $socialData['sm_social_provider'],
    ];
    $smResult = insert('social_manage', $smData);
    return $smResult;
}

function userProfile() {
    global $conn;
    if (isset($_REQUEST['u_first_name']) && $_REQUEST['u_first_name'] != '') {
        $updateArray['u_first_name'] = $_REQUEST['u_first_name'];
    } else {
        return ['status' => 1, 'message' => 'Users first name is required.'];
    }

    if (isset($_REQUEST['u_last_name']) && $_REQUEST['u_last_name'] != '') {
        $updateArray['u_last_name'] = $_REQUEST['u_last_name'];
    } else {
        return ['status' => 1, 'message' => 'Users last name is required.'];
    }
    if (isset($_REQUEST['u_address']) && $_REQUEST['u_address'] != '') {
        $updateArray['u_address'] = $_REQUEST['u_address'];
    }
    if (isset($_REQUEST['u_country']) && $_REQUEST['u_country'] != '') {
        $updateArray['u_country'] = $_REQUEST['u_country'];
    }
    if (isset($_REQUEST['u_phone']) && $_REQUEST['u_phone'] != '') {
        $updateArray['u_phone'] = $_REQUEST['u_phone'];
    }

    $updateArray['u_modified'] = date('Y-m-d H:i:s');
    $updateParams = [
        'u_id' => $_REQUEST['u_id'],
    ];

    $result = update('user', $updateArray, $updateParams);
    return ['status' => 1, 'message' => 'User profile updated success.'];
}

function getCategoryList() {
    global $conn;
    $result = [];
    $i = 0;
    $id = $_REQUEST['pc_id'];
    $select = "SELECT * FROM product_category";

    $query = mysqli_query($conn, $select);
    while ($row = mysqli_fetch_assoc($query)) {
        $result[$i] = $row;
        $i++;
    }
    if (empty($result))
        return ['status' => 1, 'message' => 'Record Not Found.'];
    return ['status' => 0, 'message' => 'Category List.', 'data' => $result];
}

function getHomeSlider() {
    global $conn;
    $result = [];
    $i = 0;
    $sl_id = 0;
    $name = 'HOME';
    $select = "SELECT * FROM slider where slider_name='" . $name . "'";
    $query = mysqli_query($conn, $select);
    $row = mysqli_fetch_assoc($query);

    $sl_id = isset($row['slider_id']) ? $row['slider_id'] : 0;

    $id = $_REQUEST['slider_id'];
    $select = "SELECT * FROM slider_img_relation where sir_slider_id=" . $sl_id;
    $query = mysqli_query($conn, $select);
    while ($row = mysqli_fetch_assoc($query)) {
        $row['sir_img'] = SITEURL . 'uploads/slider/' . $row['sir_img'];
        $result[$i] = $row;
        $i++;
    }
    if (empty($result))
        return ['status' => 1, 'message' => 'Record Not Found.'];
    return ['status' => 0, 'message' => 'Slider List.', 'data' => $result];
}

function pagination($query, $orderBy = 'desc', $orderById, $page = 1, $limit = 2) {
    global $conn;
    $sql = $query;

    $selectQuery = mysqli_query($conn, $sql);
    if (!$selectQuery) {
        return array("status" => 1, "message" => mysqli_errno());
    }
    $totalRecord = mysqli_num_rows($selectQuery);
    if ($totalRecord <= 0) {
        return array("status" => 0, "message" => "no data found", "totalRecord" => 0);
    }
    $offset = 0;
    if ($page > 1) {
        $offset = ($page - 1) * $limit;
    }
    if ($orderById != '') {
        $sql.=' ORDER BY ' . $orderById . ' ' . $orderBy;
    }
    $sql.=' LIMIT ' . $limit . ' OFFSET ' . $offset;

    $data1 = [];
    $selectQuery = mysqli_query($conn, $sql);
    $i = 0;
    while ($row = mysqli_fetch_assoc($selectQuery)) {
        $data1[$i] = $row;
        $i++;
    }

    return array("status" => 0, "message" => "success", "totalRecord" => $totalRecord, "data" => $data1);
}

/* function getProductList() {
  //p($_REQUEST);
  {
  $result = [];
  $i = 0;
  $id = $_REQUEST['category_id'];
  $select = "SELECT pd_id,pd_name,pd_price,pd_option,pd_weight FROM product_details where pd_category_id='".$id."' ";
  $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
  $limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 5;
  $orderBy = isset($_REQUEST['orderBy']) ? $_REQUEST['orderBy'] : 'desc';
  $orderById = isset($_REQUEST['orderById']) ? $_REQUEST['orderById'] : 'pd_id';
  $category_id = isset($_REQUEST['category_id']) ? $_REQUEST['category_id'] : '';
  $search_key = isset($_REQUEST['search_key']) ? $_REQUEST['search_key'] : '';
  $condition = ' WHERE 1=1 ';
  if ($category_id != '') {
  $condition.=' AND pd_category_id="' . $category_id . '" ';
  }

  if ($search_key != '') {
  $condition.=' AND (pd_name LIKE "%' . $search_key . '%" or pd_price LIKE "%' . $search_key . '%" or pd_option LIKE "%' . $search_key . '%" or pd_weight LIKE "%' . $search_key . '%" or  pd_sub_category_tags LIKE "%' . $search_key . '%" or  pd_description LIKE "%' . $search_key . '%" ) ';
  }
  $select.=$condition;
  $data = pagination($select, $orderBy, $orderById, $page, $limit);
  return $data;
  }
  } */

function getProductList() {
    global $conn;
    $result = [];
    $i = 0;
    $id = $_REQUEST['category_id'];
    $select = "SELECT pd_id,pd_name,pd_price FROM product_details where pd_category_id='" . $id . "' ";
    $query = mysqli_query($conn, $select);
    while ($row = mysqli_fetch_assoc($query)) {
        $result[$i] = $row;
        $i++;
    }
    if (empty($result))
        return ['status' => 1, 'message' => 'Record Not Found.'];
    return ['status' => 0, 'message' => 'Product List.', 'data' => $result];
}

function getProductDetail() {
    global $conn;
    $productid = '';
    if (isset($_REQUEST['pd_id']) && $_REQUEST['pd_id'] != '') {
        $productid = $_REQUEST['pd_id'];
    } else {
        return ['status' => 1, 'message' => 'Please select any product.'];
    }

    $select = "SELECT * FROM product_details where pd_id=" . $productid;
    $query = mysqli_query($conn, $select);
    if (!$query) {
        return ['status' => 1, 'message' => mysqli_error()];
    }
    $row = mysqli_fetch_assoc($query);
    return ['status' => 0, 'message' => 'success.', 'data' => $row];
}

function addWishList() {
    global $conn;
    if (isset($_REQUEST['product_id']) && $_REQUEST['product_id'] != '') {
        $insertData['wl_product_id'] = $_REQUEST['product_id'];
    } else {
        return ['status' => 1, 'message' => 'Login Required.'];
    }

    if (isset($_REQUEST['user_id']) && $_REQUEST['user_id'] != '') {
        $insertData['wl_user_id'] = ($_REQUEST['user_id']);
    } else {
        return ['status' => 1, 'message' => 'Login Required.'];
    }

    $params = $insertData;

    $checkData = queryOne('wishlists', $params);
    if (!empty($checkData)) {
        return ['status' => 1, 'message' => 'Already in wishlist.'];
    }

    $insertData['wl_created'] = date('Y-m-d H:i:s');
    $result = insert('wishlists', $insertData);
    return ['status' => 0, 'message' => 'Item added to Wish List', 'wl_id' => $result['ID']];
}

function getWishList() {
    global $conn;
//p($_REQUEST);
    {
        $result = [];
        $i = 0;
        $id = $_REQUEST['u_id'];
        $select = "SELECT * FROM wishlists where wl_user_id='" . $id . "' ";
        $query = mysqli_query($conn, $select);
        while ($row = mysqli_fetch_assoc($query)) {
            $result[$i] = $row;
            $i++;
        }
        if (empty($result))
            return ['status' => 1, 'message' => 'Record Not Found.'];
        return ['status' => 0, 'message' => 'Wish List.', 'data' => $result];
    }
}

function deleteWishList() {
    global $conn;
    $deleteParam = [];
    if (isset($_REQUEST['product_id']) && $_REQUEST['product_id'] != '') {
        $deleteParam = [
            'wl_product_id' => $_REQUEST['product_id']
        ];
    } else {
        return ['status' => 1, 'message' => 'Please Enter the Product ID'];
    }
    if (isset($_REQUEST['u_id']) && $_REQUEST['u_id'] != '') {
        $deleteParam = [
            'wl_user_id' => $_REQUEST['u_id']
        ];
    } else {
        return ['status' => 1, 'message' => 'Please Enter the User ID'];
    }

    $result = delete('wishlists', $deleteParam);
    return ['status' => 0, 'message' => 'WishList Data Deleted Successfully'];
}

function addComment() {
    global $conn;
    if (isset($_REQUEST['u_id']) && $_REQUEST['u_id'] != '') {
        $insertData['com_user_id'] = $_REQUEST['u_id'];
    } else {
        return ['status' => 1, 'message' => 'Login Required.'];
    }

    if (isset($_REQUEST['product_id']) && $_REQUEST['product_id'] != '') {
        $insertData['com_product_id'] = ($_REQUEST['product_id']);
    } else {
        return ['status' => 1, 'message' => 'Login Required.'];
    }
    if (isset($_REQUEST['com_desc']) && $_REQUEST['com_desc'] != '') {
        $insertData['com_desc'] = ($_REQUEST['com_desc']);
    } else {
        return ['status' => 1, 'message' => 'Login Required.'];
    }


    $insertData['com_created'] = date('Y-m-d H:i:s');
    $result = insert('comments', $insertData);
    return ['status' => 0, 'message' => 'Comment added on Product', 'com_id' => $result['ID']];
}

function deleteComment() {
    global $conn;
    $deleteParam = [];
    if (isset($_REQUEST['com_id']) && $_REQUEST['com_id'] != '') {
        $deleteParam = [
            'com_id' => $_REQUEST['com_id']
        ];
    } else {
        return ['status' => 1, 'message' => 'Please Enter the User ID'];
        if (isset($_REQUEST['u_id']) && $_REQUEST['u_id'] != '') {
            $deleteParam = [
                'com_user_id' => $_REQUEST['u_id']
            ];
        } else {
            return ['status' => 1, 'message' => 'Please Enter the User ID'];
        }
        if (isset($_REQUEST['product_id']) && $_REQUEST['product_id'] != '') {
            $deleteParam = [
                'com_product_id' => $_REQUEST['product_id']
            ];
        } else {
            return ['status' => 1, 'message' => 'Please Enter the Product ID'];
        }

        $result = delete('comments', $deleteParam);
        return ['status' => 0, 'message' => 'Comments Deleted Successfully'];
    }
}

function getCommentList() {
    global $conn;
//p($_REQUEST);
    {
        $result = [];
        $i = 0;
        $id = $_REQUEST['product_id'];
        $select = "SELECT * FROM comments where com_product_id='" . $id . "' ";
        $query = mysqli_query($conn, $select);
        while ($row = mysqli_fetch_assoc($query)) {
            $result[$i] = $row;
            $i++;
        }
        if (empty($result))
            return ['status' => 1, 'message' => 'Comments Not Found.'];
        return ['status' => 0, 'message' => 'Comments List.', 'data' => $result];
    }
}

function addToCart() {
    global $conn;
    if (isset($_REQUEST['cart_user_id']) && $_REQUEST['cart_user_id'] != '') {
        $insertData['cart_user_id'] = $_REQUEST['cart_user_id'];
    } else {
        return ['status' => 1, 'message' => 'Login Required.'];
    }

    if (isset($_REQUEST['cart_product_id']) && $_REQUEST['cart_product_id'] != '') {
        $insertData['cart_product_id'] = $_REQUEST['cart_product_id'];
    } else {
        return ['status' => 1, 'message' => 'Login Required.'];
    }

    $insertData['cart_created'] = date('Y-m-d H:i:s');
    $result = insert('cart', $insertData);
    return ['status' => 0, 'message' => 'Product added in Cart', 'cart_id' => $result['ID']];
}

function getFromCart() {
    global $conn;
//p($_REQUEST);
    {
        $result = [];
        $i = 0;
        $id = $_REQUEST['cart_user_id'];
        $select = "SELECT * FROM cart where cart_user_id='" . $id . "' ";
        $query = mysqli_query($conn, $select);
        while ($row = mysqli_fetch_assoc($query)) {
            $result[$i] = $row;
            $i++;
        }
        if (empty($result))
            return ['status' => 1, 'message' => 'Record Not Found.'];
        return ['status' => 0, 'message' => 'Cart Product List.', 'data' => $result];
    }
}

function deleteFromCart() {
    global $conn;
    $deleteParam = [];
    if (isset($_REQUEST['cart_id']) && $_REQUEST['cart_id'] != '') {
        $deleteParam = [
            'cart_id' => $_REQUEST['cart_id']
        ];
    } else {
        return ['status' => 1, 'message' => 'Please Enter the Cart ID'];
    }

    $result = delete('cart', $deleteParam);
    return ['status' => 0, 'message' => 'Product Successfully Deleted from Cart'];
}

function addToOrder() {
    global $conn;
    $insertData = [];
    if (isset($_REQUEST['cart_id']) && $_REQUEST['cart_id'] != '') {
        $insertData['order_cart_id'] = $_REQUEST['cart_id'];
    } else {
        return ['status' => 1, 'message' => 'Login Required.'];
    }

    if (isset($_REQUEST['order_amount']) && $_REQUEST['order_amount'] != '') {
        $insertData['order_amount'] = $_REQUEST['order_amount'];
    } else {
        return ['status' => 1, 'message' => 'Login Required.'];
    }

    $insertData['order_created'] = date('Y-m-d H:i:s');
    $result = insert('order', $insertData);
    return ['status' => 0, 'message' => 'Product added in OrderList', 'order_id' => $result['ID']];
}
