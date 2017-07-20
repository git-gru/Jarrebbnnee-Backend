<?php
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

function sendMailCore($email, $subject, $message) {
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: <Jarrebbnnee>' . "\r\n";
    $sendMail = mail($email, $subject, $message, $headers);
    if ($sendMail) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function insert($tableName, $data) {
    global $conn;
    global $l;
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
        return ['status' => 0, 'message' => $l['MSG_RI'], 'ID' => mysqli_insert_id($conn)];
    } else {
        return $checkFields;
    }
}

function update($tableName, $data, $params) {
    global $conn;
    global $l;
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
            return['status' => 0, 'message' => $l['MSG_RU']];
        } else {
            return['status' => 1, 'message' => $l['MSG_IVP']];
        }
    } else {
        return $checkFields;
    }
}

function queryOne($tableName, $params) {
    global $conn;
    global $l;
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
    global $l;
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
    global $l;
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
    global $l;
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
            return ['status' => 1, 'message' => $l['MSG_UKF'] . $key];
        }
    }
    if ($fieldFlag) {
        return ['status' => 0];
    }
}

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

function getSingleField($getFieldName, $tableName, $fieldName, $fieldValue) {
    global $conn;
    $tableData = [];
    $params = [
        $fieldName => $fieldValue
    ];
    $tableData = queryOne($tableName, $params);
    return $tableData[$getFieldName];
}

function getSingleFieldByParam($getFieldName, $tableName, $params) {
    global $conn;
    $tableData = [];

    $tableData = queryOne($tableName, $params);
    return $tableData[$getFieldName];
}

function getProductImageByProductId($product_id) {
    global $conn;
    $selectImg = "SELECT pir_image FROM product_img_rel WHERE pir_product_id = '" . $product_id . "'";
    $queryImg = mysqli_query($conn, $selectImg);
    $result = [];
    $i = 0;
    while ($rowImg = mysqli_fetch_assoc($queryImg)) {
        $result[$i] = SITEURL . "uploads/products/" . $rowImg['pir_image'];
        $i++;
    }

    return $result;
}

function getProductCommetsByProductId($productId) {
    global $conn;
    $selectCom = "SELECT user.u_first_name,u_last_name,comments.com_desc FROM comments LEFT JOIN user ON com_user_id=u_id WHERE com_product_id = '" . $productId . "'";
    $queryCom = mysqli_query($conn, $selectCom);
    $result = [];
    while ($rowCom = mysqli_fetch_assoc($queryCom)) {
        $result[] = $rowCom;
    }
    return $result;
}

function productIsInWhishlist($product_id, $userId) {
    global $conn;
    if ($userId > 0) {
        $selectImg = "SELECT wl_id FROM wishlists WHERE wl_product_id = '" . $product_id . "' and   wl_user_id= '" . $userId . "' ";
        $queryImg = mysqli_query($conn, $selectImg);
        $rowImg = mysqli_fetch_assoc($queryImg);
        if (isset($rowImg['wl_id']) && $rowImg['wl_id'] > 0) {
            return 1;
        }
        return 0;
    }
    return 0;
}

function pagination($query, $orderBy = 'desc', $orderById, $page = 1, $limit = 2) {
    global $conn;
    global $l;
    $sql = $query;

    $selectQuery = mysqli_query($conn, $sql);
    if (!$selectQuery) {
        return array("status" => 1, "message" => mysqli_errno());
    }
    $totalRecord = mysqli_num_rows($selectQuery);
    if ($totalRecord <= 0) {
        return array("status" => 0, "message" => $l['MSG_NDF'], "totalRecord" => 0);
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
    $nextPage = 0;
    if ($limit < $totalRecord) {
        $currentRecord = $limit * $page;
        if ($currentRecord < $totalRecord) {
            $nextPage = $page + 1;
        }
    }

    return array("status" => 0, "message" => $l['MSG_S'], "totalRecord" => $totalRecord, "data" => $data1, 'nextPage' => $nextPage);
}

//common function END here 

function loginAdmin() {
    global $conn;
    global $l;
    $result = ragisterDevice($_REQUEST);
    if (isset($result['status']) && $result['status'] == 0) {
        if (isset($_REQUEST['email']) && $_REQUEST['email'] != '') {
            $email = $_REQUEST['email'];
        } else {
            return ['status' => 1, 'message' => $l['MSG_PEEA']];
        }

        if (isset($_REQUEST['password']) && $_REQUEST['password'] != '') {
            $password = $_REQUEST['password'];
        } else {
            return ['status' => 1, 'message' => $l['MSG_PEYP']];
        }

        $checkByEmail = getByFieldName('admin', 'email', $email);
        if (!empty($checkByEmail)) {
            if (isset($checkByEmail['password']) && $checkByEmail['password'] != '') {
                if ($checkByEmail['password'] == md5($password)) {
                    $_REQUEST['user_id'] = $checkByEmail['id'];
                    ragisterDevice($_REQUEST);
                    return ['status' => 0, 'message' => $l['MSG_AD'], 'data' => $checkByEmail];
                } else {
                    return ['status' => 1, 'message' => $l['MSG_EAPINM']];
                }
            } else {
                return ['status' => 1, 'message' => $l['MSG_UNR']];
            }
        } else {
            return ['status' => 1, 'message' => $l['MSG_IEA']];
        }
    } else {
        return $result;
    }
}

function countryList() {
    global $conn;
    global $l;
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
        return ['status' => 1, 'message' => $l['MSG_RNF']];
    return ['status' => 0, 'message' => $l['MSG_CL'], 'data' => $result];
}

function userRagister($socialFlag = 1) {
    global $conn;
     global $l;
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
            return ['status' => 0, 'message' => $l['MSG_PEEA']];
        }

        if (isset($_REQUEST['u_password']) && $_REQUEST['u_password'] != '') {
            $insertData['u_password'] = md5($_REQUEST['u_password']);
        } else {
            return ['status' => 0, 'message' => $l['MSG_PEYP']];
        }

        if (isset($_REQUEST['u_first_name']) && $_REQUEST['u_first_name'] != '') {
            $insertData['u_first_name'] = $_REQUEST['u_first_name'];
        } else {
            return ['status' => 0, 'message' => $l['MSG_PEYFN']];
        }

        if (isset($_REQUEST['u_last_name']) && $_REQUEST['u_last_name'] != '') {
            $insertData['u_last_name'] = $_REQUEST['u_last_name'];
        } else {
            return ['status' => 0, 'message' => $l['MSG_PEYLN']];
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
            return ['status' => 1, 'message' => $l['MSG_EAR']];
        } else {
            $insertData['u_crteated'] = date('Y-m-d H:i:s');
            $result = insert('user', $insertData);
            if (isset($result['ID']) && $result['ID'] != '') {
                $_REQUEST['u_id'] = $result['ID'];
                ragisterDevice($_REQUEST);
                $params = [
                    'u_id' => $result['ID'],
                ];
                $_REQUEST['u_id'] = $result['ID'];
                $_REQUEST['u_address1'] = $_REQUEST['u_address'];
                addMyAddressBook();
                $userData = queryOne('user', $params);
                return ['status' => 0, 'message' => $l['MSG_URS'], 'u_id' => $result['ID']];
            } else {
                return $result;
            }
        }
    }
}

function userLogin() {
    global $conn;
    global $l;
    $result = ragisterDevice($_REQUEST);
    if (isset($result['status']) && $result['status'] == 0) {
        if (isset($_REQUEST['u_email']) && $_REQUEST['u_email'] != '') {
            $email = $_REQUEST['u_email'];
        } else {
            return ['status' => 1, 'message' => $l['MSG_PEEA']];
        }

        if (isset($_REQUEST['u_password']) && $_REQUEST['u_password'] != '') {
            $password = $_REQUEST['u_password'];
        } else {
            return ['status' => 1, 'message' => $l['MSG_PEYP']];
        }

        $checkByEmail = getByFieldName('user', 'u_email', $email);

        if (!empty($checkByEmail)) {
            if (isset($checkByEmail['u_password']) && $checkByEmail['u_password'] != '') {
                if ($checkByEmail['u_password'] == md5($password)) {
                    $_REQUEST['user_id'] = $checkByEmail['u_id'];
                    ragisterDevice($_REQUEST);
                    return ['status' => 0, 'message' => $l['MSG_UD'], 'data' => $checkByEmail];
                } else {
                   
                    return ['status' => 1, 'message' => $l['MSG_EAPINM']];
                }
            } else {
                return ['status' => 1, 'message' => $l['MSG_UNR']];
            }
        } else {
            return ['status' => 1, 'message' => $l['MSG_IEA']];
        }
    } else {
        return $result;
    }
}

function socialLogin() {
    global $conn;
    global $l;
    $result = ragisterDevice($_REQUEST);
    if (isset($_REQUEST['sm_social_provider']) && $_REQUEST['sm_social_provider'] != '') {
        $social_provider_type = strtolower($_REQUEST['sm_social_provider']);
    } else {
        return ['status' => 1, 'message' => $l['MSG_UDSP']];
    }

    if (isset($_REQUEST['sm_social_provider_id']) && $_REQUEST['sm_social_provider_id'] != '') {
        $sm_social_provider_id = strtolower($_REQUEST['sm_social_provider_id']);
    } else {
        return ['status' => 1, 'message' => $l['MSG_UDSPI']];
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
                return ['status' => 0, 'message' => $l['MSG_LSUCC'], 'data' => $userData];
            } else {
                return ['status' => 1, 'messsage' => $l['MSG_YDIMMPCAA']];
            }
        } else {
            return ['status' => 1, 'message' => $l['MSG_SWWPTA']];
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
                    return ['status' => 1, 'message' => $l['MSG_YDIMMPCAA']];
                }
            } else {
                return ['status' => 1, 'message' => $l['MSG_YDIMMPCAA']];
            }
        } else {
            return $ragisterData;
        }
    }
}

function ragisterDevice($data) {
    global $conn;
    global $l;
    if (isset($data['device_type']) && $data['device_type'] != '') {
        $insertData['mr_device_type'] = $data['device_type'];
    } else {
        return ['status' => 1, 'message' => $l['MSG_IVDTSSBAOI']];
    }

    if (isset($data['device_token']) && $data['device_token'] != '') {
        $insertData['mr_device_token'] = $data['device_token'];
    } else {
        return ['status' => 1, 'message' => $l['MSG_IVDT']];
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
        return ['status' => 0, 'message' => $l['MSG_DVDS']];
    } else {
        $insertData['mr_created'] = date('Y-m-d H:i:s');
        insert('mobile_ragister', $insertData);
        return ['status' => 0, 'message' => $l['MSG_DRDS']];
    }
}

function checkExistSocial($id, $type) {
    global $conn;
    global $l;
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
    global $l;
    $smData = [
        'sm_user_id' => $userId,
        'sm_social_provider_id' => $socialData['sm_social_provider_id'],
        'sm_social_provider' => $socialData['sm_social_provider'],
    ];
    $smResult = insert('social_manage', $smData);
    return $smResult;
}

function userProfile() {
    global $l;
    global $conn;
    if (isset($_REQUEST['u_first_name']) && $_REQUEST['u_first_name'] != '') {
        $updateArray['u_first_name'] = $_REQUEST['u_first_name'];
    } else {
        return ['status' => 1, 'message' => $l['MSG_UFNIRQ']];
    }

    if (isset($_REQUEST['u_last_name']) && $_REQUEST['u_last_name'] != '') {
        $updateArray['u_last_name'] = $_REQUEST['u_last_name'];
    } else {
        return ['status' => 1, 'message' => $l['MSG_ULNIRQ']];
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
    return ['status' => 1, 'message' => $l['MSG_UPUSFLLY']];
}

function forgotPassword() {
    global $l;
    $email_id = (isset($_REQUEST['u_email']) ? $_REQUEST['u_email'] : '');
    $params = [
        'u_email' => $email_id
    ];

    $getuserDetails = queryOne('user', $params);
    if (!empty($getuserDetails)) {
        $newPassword = generateRandomString(10);
        $updatePassword['u_password'] = md5($newPassword);
        $result = update('user', $updatePassword, $params);
        $subject = "New Password";

        $body = "Your New Password is : $newPassword";
        sendMailCore($email_id, $subject, $body);
        return ['status' => '0', 'message' => $l['MSG_YNPISTYE']];
    } else {
        return ['status' => '0', 'message' => $l['MSG_UWTINR']];
    }
}

function changePassword() {
    global $l;
    $oldPassword = isset($_REQUEST['u_oldpassword']) ? $_REQUEST['u_oldpassword'] : '';
    $newPassword = isset($_REQUEST['u_newpassword']) ? $_REQUEST['u_newpassword'] : '';
    $u_id = isset($_REQUEST['u_id']) ? $_REQUEST['u_id'] : '';
    $params = [
        'u_id' => $u_id
    ];
    $getuserDetails = queryOne('user', $params);
    if (!empty($getuserDetails)) {
        if ($getuserDetails['u_password'] == md5($oldPassword)) {
            $updatePassword['u_password'] = md5($newPassword);
            $result = update('user', $updatePassword, $params);
            return ['status' => '0', 'message' => $l['MSG_UPICSU']];
        } else {
            return ['status' => '0', 'message' => $l['MSG_EPIIC']];
        }
    } else {
        return ['status' => '0', 'message' => $l['MSG_UWTINR']];
    }
}

function getCategoryList() {
    global $conn;
    global $l;
    $result = [];
    $i = 0;
//    $id = $_REQUEST['pc_id'];
    $select = "SELECT * FROM product_category";

    $query = mysqli_query($conn, $select);
    while ($row = mysqli_fetch_assoc($query)) {
        $result[$i] = $row;
        $i++;
    }
    if (empty($result))
        return ['status' => 1, 'message' => $l['MSG_RNF']];
    return ['status' => 0, 'message' => $l['MSG_CLI'], 'data' => $result];
}

function getHomeSlider() {
    global $conn;
    global $l;
    $result = [];
    $i = 0;
    $sl_id = 0;
    $name = 'HOME';
    $select = "SELECT * FROM slider where slider_name='" . $name . "'";
    //$select = "SELECT * FROM slider";
    $query = mysqli_query($conn, $select);
    $data = mysqli_fetch_assoc($query);

    $sl_id = isset($data['slider_id']) ? $data['slider_id'] : 0;

    $select = "SELECT * FROM slider_img_relation where sir_slider_id=" . $sl_id;
    //$select = "SELECT * FROM slider_img_relation where sir_slider_id='" .$row['slider_id']."'";
    $queryimg = mysqli_query($conn, $select);
    while ($row = mysqli_fetch_assoc($queryimg)) {
        $row['sir_img'] = SITEURL . 'uploads/slider/' . $row['sir_img'];
        $result[$i] = $row;
        $i++;
    }
    if (empty($result))
        return ['status' => 1, 'message' => $l['MSG_RNF']];
    return ['status' => 0, 'message' => $l['MSG_SLI'], 'data' => $result];
}

function getProductList() {
    global $conn;
    global $l;
    $result = [];
    $i = 0;
    $select = "SELECT pd_id,pd_name,pd_price,pd_option,pd_weight,pd_refer_id FROM product_details ";
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

    $mresult = [];
    if (isset($data['data']) && !empty($data)) {
        foreach ($data['data'] as $row) {
            $productImage = getProductImageByProductId($row['pd_refer_id']);
            $row['images'] = isset($productImage[0]) ? $productImage[0] : '';
            $mresult[] = $row;
        }
    }
    if (empty($mresult))
        return ['status' => 1, 'message' => $l['MSG_RNF']];

    return ['status' => 0, 'message' => $l['MSG_PLI'], 'data' => $mresult, 'totalRecord' => $data['totalRecord'], 'nextPage' => $data['nextPage']];
}

function getProductDetail() {
    global $conn;
    global $l;
    $result = [];
    $i = 0;
    $u_id = isset($_REQUEST['u_id']) ? $_REQUEST['u_id'] : 0;
    $productid = '';
    if (isset($_REQUEST['product_id']) && $_REQUEST['product_id'] != '') {
        $productid = $_REQUEST['product_id'];
    } else {
        return ['status' => 1, 'message' => $l['MSG_PSAP']];
    }

    $select = "SELECT pd_id,pd_refer_id,pd_name,pd_price,pd_description,pd_option,pd_weight FROM product_details where pd_refer_id=" . $productid;
    $query = mysqli_query($conn, $select);
    $row = mysqli_fetch_assoc($query);
    if (isset($row['pd_id'])) {
        $row['images'] = getProductImageByProductId($row['pd_refer_id']);
        $row['comments'] = getProductCommetsByProductId($row['pd_refer_id']);
        $row['is_in_wishlist'] = productIsInWhishlist($row['pd_refer_id'], $u_id);
    }
    if (empty($row))
        return ['status' => 1, 'message' => $l['MSG_RNF']];
    return ['status' => 0, 'message' => $l['MSG_PDLI'], 'data' => $row];
}

function getFilterResult() {
    global $conn;
    global $l;
    $productName = isset($_REQUEST['product_name']) ? $_REQUEST['product_name'] : '';
    $searchbyMinMax = '';
    $category = '';
    if (isset($_REQUEST['min_price']) && $_REQUEST['min_price'] != '') {
        $minPrice = isset($_REQUEST['min_price']) ? $_REQUEST['min_price'] : '';
        $maxPrice = isset($_REQUEST['max_price']) ? $_REQUEST['max_price'] : '';
        $searchbyMinMax = " AND (pd_price BETWEEN $minPrice AND $maxPrice)";
    }
    $productCategory = '';
    if (isset($_REQUEST['category_id']) && $_REQUEST['category_id'] != '') {
        $categoryId = isset($_REQUEST['category_id']) ? $_REQUEST['category_id'] : '';
        $category = trim(trim($categoryId), ',');
        $cateArray = explode(',', $category);
        $productCategory = " AND pd_category_id in (" . implode(',', $cateArray) . ")";
    }
    $searchbyproductRefId='';
     if (isset($_REQUEST['pd_refer_id']) && $_REQUEST['pd_refer_id'] != '') {
        $productRefId = isset($_REQUEST['pd_refer_id']) ? $_REQUEST['pd_refer_id'] : '';
        $searchbyproductRefId = " AND (pd_refer_id='$productRefId')";
    }

//    p($cateArray);
    $select = "SELECT pd_id,pd_name,pd_price,pd_category_id,pd_refer_id FROM `product_details` WHERE `pd_name` LIKE '%" . $productName . "%' $searchbyMinMax $productCategory $searchbyproductRefId";
    $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
    $limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 5;
    $orderBy = isset($_REQUEST['orderBy']) ? $_REQUEST['orderBy'] : 'desc';
    $orderById = isset($_REQUEST['orderById']) ? $_REQUEST['orderById'] : 'pd_id';
    $data = pagination($select, $orderBy, $orderById, $page, $limit);
    
    
    $mresult = [];
    if (isset($data['data']) && !empty($data)) {
        foreach ($data['data'] as $row) {
            $productImage = getProductImageByProductId($row['pd_refer_id']);
            $row['images'] = isset($productImage[0]) ? $productImage[0] : '';
            $mresult[] = $row;
        }
    }
    if (empty($mresult))
        return ['status' => 1, 'message' => $l['MSG_RNF']];

        return ['status' => 0, 'message' => $l['MSG_PLI'], 'data' => $mresult, 'totalRecord' => $data['totalRecord'], 'nextPage' => $data['nextPage']];

}

function addWishList() {
    global $conn;
    global $l;
    if (isset($_REQUEST['product_id']) && $_REQUEST['product_id'] != '') {
        $insertData['wl_product_id'] = $_REQUEST['product_id'];
    } else {
        return ['status' => 1, 'message' => $l['MSG_LR']];
    }

    if (isset($_REQUEST['u_id']) && $_REQUEST['u_id'] != '') {
        $insertData['wl_user_id'] = ($_REQUEST['u_id']);
    } else {
        return ['status' => 1, 'message' => $l['MSG_LR']];
    }

    $params = $insertData;

    $checkData = queryOne('wishlists', $params);
    if (!empty($checkData)) {
        return ['status' => 1, 'message' => $l['MSG_ALRIWLI']];
    }

    $insertData['wl_created'] = date('Y-m-d H:i:s');
    $result = insert('wishlists', $insertData);
    return ['status' => 0, 'message' => $l['MSG_IATWLI'], 'wl_id' => $result['ID']];
}

function getWishList() {
    global $conn;
    global $l;
    $result = [];
    $i = 0;
    $id = $_REQUEST['u_id'];
    $select = "SELECT * FROM wishlists where wl_user_id='" . $id . "' ";
    $query = mysqli_query($conn, $select);

    while ($row = mysqli_fetch_assoc($query)) {
        $params = [
            'pd_refer_id' => $row['wl_product_id']
        ];
        $productPrice = queryOne('product_details', $params);
        $row['product_name'] = isset($productPrice['pd_name']) ? $productPrice['pd_name'] : '';
        $row['price'] = isset($productPrice['pd_price']) ? $productPrice['pd_price'] : '';
        $productImage = getProductImageByProductId($row['wl_product_id']);
        $row['images'] = isset($productImage[0]) ? $productImage[0] : '';
        $result[$i] = $row;
        $i++;
    }
    if (empty($result))
        return ['status' => 1, 'message' => $l['MSG_RNF']];
    return ['status' => 0, 'message' => $l['MSG_WLI'], 'data' => $result];
}

function deleteWishList() {
    global $conn;
    global $l;
    $deleteParam = [];
    if (isset($_REQUEST['product_id']) && $_REQUEST['product_id'] != '') {
        $productid = $_REQUEST['product_id'];
    } else {
        return ['status' => 1, 'message' => $l['MSG_PETPI']];
    }
    if (isset($_REQUEST['u_id']) && $_REQUEST['u_id'] != '') {
        $userid = $_REQUEST['u_id'];
    } else {
        return ['status' => 1, 'message' => $l['MSG_PETUI']];
    }
   
    $deleteParam = [
            'wl_user_id' => $userid,
            'wl_product_id' => $productid
        ];

    $result = delete('wishlists', $deleteParam);
    return ['status' => 0, 'message' => $l['MSG_WLIDDS']];
}

function addComment() {
    global $conn;
    global $l;
    if (isset($_REQUEST['u_id']) && $_REQUEST['u_id'] != '') {
        $insertData['com_user_id'] = $_REQUEST['u_id'];
    } else {
        return ['status' => 1, 'message' => $l['MSG_LR']];
    }

    if (isset($_REQUEST['product_id']) && $_REQUEST['product_id'] != '') {
        $insertData['com_product_id'] = ($_REQUEST['product_id']);
    } else {
        return ['status' => 1, 'message' => $l['MSG_LR']];
    }
    if (isset($_REQUEST['com_desc']) && $_REQUEST['com_desc'] != '') {
        $insertData['com_desc'] = ($_REQUEST['com_desc']);
    } else {
        return ['status' => 1, 'message' => $l['MSG_LR']];
    }


    $insertData['com_created'] = date('Y-m-d H:i:s');
    $result = insert('comments', $insertData);
    return ['status' => 0, 'message' => $l['MSG_CAOP'], 'com_id' => $result['ID']];
}

function deleteComment() {
    global $conn;
    global $l;
    $deleteParam = [];
    if (isset($_REQUEST['com_id']) && $_REQUEST['com_id'] != '') {
        $deleteParam = [
            'com_id' => $_REQUEST['com_id']
        ];
    } else {
        return ['status' => 1, 'message' => $l['MSG_PETCI']];
    }
    if (isset($_REQUEST['u_id']) && $_REQUEST['u_id'] != '') {
        $deleteParam = [
            'com_user_id' => $_REQUEST['u_id']
        ];
    } else {
        return ['status' => 1, 'message' => $l['MSG_PETUI']];
    }
    if (isset($_REQUEST['product_id']) && $_REQUEST['product_id'] != '') {
        $deleteParam = [
            'com_product_id' => $_REQUEST['product_id']
        ];
    } else {
        return ['status' => 1, 'message' => $l['MSG_PETPI']];
    }

    $result = delete('comments', $deleteParam);
    return ['status' => 0, 'message' => $l['MSG_CDS']];
}

function getCommentList() {
    global $conn;
    global $l;
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
            return ['status' => 1, 'message' => $l['MSG_CNF']];
        return ['status' => 0, 'message' => $l['MSG_CL'], 'data' => $result];
    }
}

function getCartItems($cart_id) {
//    global $l;
    $params_cart_items = ['ci_cart_id' => $cart_id];
    $myCartItems = queryAll('cart_items', $params_cart_items);
    $cartitems = [];
    if (!empty($myCartItems)) {

        foreach ($myCartItems as $item) {
            $product_data = queryOne('product_details', ['pd_refer_id' => $item['ci_product_id']]);
            $item['product_name'] = isset($product_data['pd_name']) ? $product_data['pd_name'] : '';
            $productImage = getProductImageByProductId($item['ci_product_id']);
            $item['images'] = isset($productImage[0]) ? $productImage[0] : '';
            $cartitems[] = $item;
        }
    }
    return $cartitems;
}

function getMyRunnigCart($u_id) {
    global $conn;
//    global $l;
    $select = "SELECT * FROM cart where cart_u_id='" . $u_id . "' and cart_status='0'";

    $query = mysqli_query($conn, $select);
    if (!$query) {
        die(mysqli_error($conn));
    }
    $row = mysqli_fetch_assoc($query);

    if (isset($row['cart_id'])) {
        return $row['cart_id'];
    }
    return createCart($u_id);
}

function createCart($u_id) {
    $insData['cart_key'] = md5(rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . time() . rand(0, 9));
    $insData['cart_sub_total'] = 0;
    $insData['cart_discount'] = 0;
    $insData['cart_coupon_code'] = '';
    $insData['cart_grand_total'] = 0;
    $insData['cart_u_id'] = $u_id;
    $result = insert('cart', $insData);
    return $result['ID'];
}

function updateCartFinal($cart_id, $coupon_data = array()) {
    global $l;
    $myCartItems = getCartItems($cart_id);
    $cart_sub_total = 0;

    $cart_params = ['cart_id' => $cart_id];
    $cart_data = queryOne('cart', $cart_params);

    if (!empty($myCartItems)) {
        foreach ($myCartItems as $item) {
            $cart_sub_total = $cart_sub_total + $item['ci_price_subtotal'];
        }
    }
    $insData['cart_sub_total'] = $cart_sub_total;
    $insData['cart_discount'] = 0;
    $insData['cart_coupon_code'] = '';
    $insData['cart_grand_total'] = $cart_sub_total;
    $message = '';
    $discount = 0;
    if (!empty($coupon_data)) {
        $cc_discount_type = $coupon_data['cc_discount_type'];
        $cc_discount_amount = $coupon_data['cc_discount_amount'];


        if (!isset($cart_data['cart_id']) || $cart_data['cart_id'] <= 0) {
            return ['status' => 1, 'message' => $l['MSG_CNF']];
        }

        if ($cc_discount_type == 'flat') {
            $discount = $cc_discount_amount;
        } else {
            $famount = ($cc_discount_amount * $cart_sub_total) / 100;
            $discount = number_format($famount, 2);
        }
        $insData['cart_coupon_code'] = $coupon_data['cc_code'];
        $insData['cart_discount'] = $discount;
        $insData['cart_grand_total'] = ($cart_sub_total - $discount);
        $message = $cc_discount_type . ' ' . $cc_discount_amount . $l['MSG_ASOYAYS'] . $discount;
    }

    $params_cart = ['cart_id' => $cart_id];
    $result = update('cart', $insData, $params_cart);
    return ['status' => 0, 'message' => $message, 'discount' => $discount];
}

function addToCart() {
    global $conn;
global $l;
    $user_id = 0;

    if (isset($_REQUEST['u_id']) && $_REQUEST['u_id'] != '') {
        $user_id = $_REQUEST['u_id'];
    } else {
        return ['status' => 1, 'message' => $l['MSG_LR']];
    }

    if (isset($_REQUEST['product_id']) && $_REQUEST['product_id'] != '') {
        $insertData['ci_product_id'] = $_REQUEST['product_id'];
    } else {
        return ['status' => 1, 'message' => $l['MSG_PSP']];
    }
    if (isset($_REQUEST['product_qty']) && $_REQUEST['product_qty'] != '') {
        $insertData['ci_product_qty'] = $_REQUEST['product_qty'];
    } else {
        return ['status' => 1, 'message' => $l['MSG_PAALOQ']];
    }
    $cart_id = getMyRunnigCart($user_id);

    $pd_price = getSingleField('pd_price', 'product_details', 'pd_refer_id', $_REQUEST['product_id']);
    $param_items = ['ci_product_id' => $_REQUEST['product_id'], 'ci_cart_id' => $cart_id];
    $ci_data = queryOne('cart_items', $param_items);
    $insertData['ci_cart_id'] = $cart_id;
    $insertData['ci_user_id'] = $user_id;
    $insertData['ci_price'] = $pd_price;
    $insertData['ci_price_subtotal'] = ($pd_price * $_REQUEST['product_qty']);
    $insertData['ci_created'] = date('Y-m-d H:i:s');

    if (isset($ci_data['ci_id']) && $ci_data['ci_id'] > 0) {
        $insertData['ci_product_qty'] = $_REQUEST['product_qty'] + $ci_data['ci_product_qty'];
        $insertData['ci_price_subtotal'] = ($pd_price * $insertData['ci_product_qty']);
        $params_ci_update = ['ci_id' => $ci_data['ci_id']];
        $result = update('cart_items', $insertData, $params_ci_update);
    } else {
        $result = insert('cart_items', $insertData);
    }
    updateCartFinal($cart_id);
    return ['status' => 0, 'message' => $l['MSG_PAIC'], 'cart_id' => $cart_id];
}

function getMyCart() {
    global $conn;
    global $l;
    $u_id = isset($_REQUEST['u_id']) ? $_REQUEST['u_id'] : 0;
    $select = "SELECT * FROM cart where cart_u_id='" . $u_id . "' and cart_status='0'";

    $query = mysqli_query($conn, $select);
    if (!$query) {
        die(mysqli_error($conn));
    }
    $data = mysqli_fetch_assoc($query);


    if (isset($data['cart_id'])) {
        $cartItems = getCartItems($data['cart_id']);
        $data['cart_items'] = $cartItems;
        return ['status' => 0, 'message' => $l['MSG_MCL'], 'data' => $data];
    }
    return ['status' => 1, 'message' => $l['MSG_EC']];
}

function updateQuntityCartItem() {
    global $conn;
    global $l;
    $user_id = '';
    $ci_id = '';
    $product_qty = 0;
    if (isset($_REQUEST['u_id']) && $_REQUEST['u_id'] != '') {
        $user_id = $_REQUEST['u_id'];
    } else {
        return ['status' => 1, 'message' => $l['MSG_PETUI']];
    }
    if (isset($_REQUEST['ci_id']) && $_REQUEST['ci_id'] != '') {
        $ci_id = $_REQUEST['ci_id'];
    } else {
        return ['status' => 1, 'message' => $l['MSG_PETCII']];
    }
    if (isset($_REQUEST['product_qty']) && $_REQUEST['product_qty'] != '' && $_REQUEST['product_qty'] > 0) {
        $product_qty = $_REQUEST['product_qty'];
    } else {
        return ['status' => 1, 'message' => $l['MSG_PUTPQ']];
    }

    $ci_params = ['ci_id' => $ci_id];
    $ci_data = queryOne('cart_items', $ci_params);
    if (isset($ci_data['ci_id']) && $ci_data['ci_id'] > 0) {
        $updateParam = [
            'ci_id' => $ci_data['ci_id']
        ];
        $pd_price = getSingleField('pd_price', 'product_details', 'pd_refer_id', $ci_data['ci_product_id']);
        $updateParamas['ci_product_qty'] = $_REQUEST['product_qty'];
        $updateParamas['ci_price_subtotal'] = $_REQUEST['product_qty'] * $pd_price;
        $result = update('cart_items', $updateParamas, $updateParam);
        updateCartFinal($ci_data['ci_cart_id']);
        return ['status' => 0, 'message' => $l['MSG_PSUIC']];
    }
    return ['status' => 1, 'message' => $l['MSG_CINF']];
}

function applyCoupon() {
    global $conn;
    global $l;
    $user_id = '';
    $cart_id = '';
    $coupon_code = '';
    if (isset($_REQUEST['u_id']) && $_REQUEST['u_id'] != '') {
        $user_id = $_REQUEST['u_id'];
    } else {
        return ['status' => 1, 'message' => $l['MSG_PETUI']];
    }
    if (isset($_REQUEST['cart_id']) && $_REQUEST['cart_id'] != '') {
        $cart_id = $_REQUEST['cart_id'];
    } else {
        return ['status' => 1, 'message' => $l['MSG_PSC']];
    }
    if (isset($_REQUEST['coupon_code']) && $_REQUEST['coupon_code'] != '') {
        $coupon_code = $_REQUEST['coupon_code'];
    } else {
        return ['status' => 1, 'message' => $l['MSG_PECC']];
    }


    $coupon_params = ['cc_code' => $coupon_code];
    $coupon_data = queryOne('coupon_code', $coupon_params);
    if (isset($coupon_data['cc_id']) && $coupon_data['cc_id'] > 0) {
        $result = updateCartFinal($cart_id, $coupon_data);

        return $result;
    }
    return ['status' => 1, 'message' => $l['MSG_CNF']];
}

function deleteFromCart() {
    global $conn;
    global $l;
    $user_id = '';
    $ci_id = '';
    if (isset($_REQUEST['u_id']) && $_REQUEST['u_id'] != '') {
        $user_id = $_REQUEST['u_id'];
    } else {
        return ['status' => 1, 'message' => $l['MSG_PETUI']];
    }
    if (isset($_REQUEST['ci_id']) && $_REQUEST['ci_id'] != '') {
        $ci_id = $_REQUEST['ci_id'];
    } else {
        return ['status' => 1, 'message' => $l['MSG_PETCI']];
    }

    $ci_params = ['ci_id' => $ci_id];
    $ci_data = queryOne('cart_items', $ci_params);
    if (isset($ci_data['ci_id']) && $ci_data['ci_id'] > 0) {
        $deleteParam = [
            'ci_id' => $ci_data['ci_id']
        ];
        $result = delete('cart_items', $deleteParam);
        updateCartFinal($ci_data['ci_cart_id']);
        return ['status' => 0, 'message' => $l['MSG_PSDFC']];
    }
    return ['status' => 1, 'message' => $l['MSG_CINF']];
}

function addToOrder() {
    global $conn;
    global $l;
    $insertData = [];

    if (isset($_REQUEST['u_id']) && $_REQUEST['u_id'] != '') {
        $insertData['cart_user_id'] = $_REQUEST['u_id'];
    } else {
        return ['status' => 1, 'message' => $l['MSG_LR']];
    }
    if (isset($_REQUEST['status']) && $_REQUEST['status'] != '') {
        $updateArray['cart_status'] = $_REQUEST['status'];
    }
    $select = "select * from cart where cart_status= '" . $_REQUEST['status'] . "'";
    $query = mysqli_query($conn, $select);
    $row = mysqli_fatch_assoc($query);
    if (isset($_REQUEST['cart_id']) && $_REQUEST['cart_id'] != '') {
        $insertData['order_cart_id'] = $_REQUEST['cart_id'];
    } else {
        return ['status' => 1, 'message' => $l['MSG_LR']];
    }

    if (isset($_REQUEST['order_amount']) && $_REQUEST['order_amount'] != '') {
        $insertData['order_amount'] = $_REQUEST['order_amount'];
    } else {
        return ['status' => 1, 'message' => $l['MSG_LR']];
    }

    $insertData['order_created'] = date('Y-m-d H:i:s');
    $result = insert('order', $insertData);
    return ['status' => 0, 'message' => $l['MSG_PAIOL'], 'order_id' => $result['ID']];

    $updateArray['cart_modifide'] = date('Y-m-d H:i:s');
    $updateParams = [
        'cart_id' => $_REQUEST['cart_id'],
    ];

    $result = update('cart', $updateArray, $updateParams);
    return ['status' => 1, 'message' => $l['MSG_CSUS']];
}

function addMyAddressBook() {
    global $conn;
    global $l;
    $id = isset($_REQUEST['u_id']) ? $_REQUEST['u_id'] : 0;
    $row = queryOne('user', ['u_id' => $id]);
    if (!empty($row)) {
        $insertData['ab_u_id'] = $row['u_id'];

        if (isset($_REQUEST['u_first_name']) && $_REQUEST['u_first_name'] != '') {
            $insertData['ab_first_name'] = $_REQUEST['u_first_name'];
        } else {
            return ['status' => 1, 'message' => $l['MSG_PEYFN']];
        }

        if (isset($_REQUEST['u_last_name']) && $_REQUEST['u_last_name'] != '') {
            $insertData['ab_last_name'] = $_REQUEST['u_last_name'];
        } else {
            return ['status' => 1, 'message' => $l['MSG_PEYLN']];
        }
        if (isset($_REQUEST['u_address1']) && $_REQUEST['u_address1'] != '') {
            $insertData['ab_add1'] = $_REQUEST['u_address1'];
        } else {
            return ['status' => 1, 'message' => $l['MSG_AR']];
        }
        if (isset($_REQUEST['u_address2']) && $_REQUEST['u_address2'] != '') {
            $insertData['ab_add2'] = $_REQUEST['u_address2'];
        }

        if (isset($_REQUEST['u_country']) && $_REQUEST['u_country'] != '') {
            $insertData['ab_country'] = $_REQUEST['u_country'];
        } else {
            return ['status' => 1, 'message' => $l['MSG_PEYC']];
        }
        if (isset($_REQUEST['u_state']) && $_REQUEST['u_state'] != '') {
            $insertData['ab_state'] = $_REQUEST['u_state'];
        } else {
            return ['status' => 1, 'message' => $l['MSG_PEYS']];
        }

        if (isset($_REQUEST['u_city']) && $_REQUEST['u_city'] != '') {
            $insertData['ab_city'] = $_REQUEST['u_city'];
        } else {
            return ['status' => 1, 'message' => $l['MSG_PEC']];
        }
        if (isset($_REQUEST['u_pincode']) && $_REQUEST['u_pincode'] != '') {
            $insertData['ab_pincode'] = $_REQUEST['u_pincode'];
        } else {
            return ['status' => 1, 'message' => $l['MSG_PEZPC']];
        }
        if (isset($_REQUEST['u_phone']) && $_REQUEST['u_phone'] != '') {
            $insertData['ab_phone'] = $_REQUEST['u_phone'];
        } else {
            return ['status' => 1, 'message' => $l['MSG_PEYPN']];
        }

        $insertData['ab_created'] = date('Y-m-d H:i:s');
        $result = insert('address_book', $insertData);

        return ['status' => 0, 'message' => $l['MSG_AIS'], 'ab_id' => $result['ID']];
    }
    return ['status' => 1, 'message' => $l['MSG_UWTINR']];
}

function deleteFromAddressBook() {
    global $conn;
    global $l;
    $deleteParam = [];
    if (isset($_REQUEST['ab_id']) && $_REQUEST['ab_id'] != '') {
        $abid = $_REQUEST['ab_id'];
    } else {
        return ['status' => 1, 'message' => $l['MSG_PETABI']];
    }
    if (isset($_REQUEST['u_id']) && $_REQUEST['u_id'] != '') {
        $userid = $_REQUEST['u_id'];
    } else {
        return ['status' => 1, 'message' => $l['MSG_PETUI']];
    }
    $deleteParam = [
            'ab_u_id' => $userid,
        'ab_id' => $abid
        ];

    $result = delete('address_book', $deleteParam);
    return ['status' => 0, 'message' => $l['MSG_ADSF']];
}

function getMyAddressBook() {
    global $conn;
    global $l;
    $result = [];
    $i = 0;
    $id = isset($_REQUEST['u_id']) ? $_REQUEST['u_id'] : 0;
    $select = "SELECT * FROM address_book where ab_u_id='" . $id . "' ";
    $query = mysqli_query($conn, $select);
    while ($row = mysqli_fetch_assoc($query)) {
        $result[$i] = $row;
        $i++;
    }
    if (empty($result))
        return ['status' => 1, 'message' => $l['MSG_ANF']];
    return ['status' => 0, 'message' => $l['MSG_MAL'], 'data' => $result];
}

function updateMyAddressbook() {
    global $conn;
    global $l;
    if (isset($_REQUEST['u_first_name']) && $_REQUEST['u_first_name'] != '') {
        $updateArray['ab_first_name'] = $_REQUEST['u_first_name'];
    } else {
        return ['status' => 1, 'message' => $l['MSG_UFNIR']];
    }
    if (isset($_REQUEST['u_last_name']) && $_REQUEST['u_last_name'] != '') {
        $updateArray['ab_last_name'] = $_REQUEST['u_last_name'];
    } else {
        return ['status' => 1, 'message' => $l['MSG_UFLIR']];
    }
    if (isset($_REQUEST['u_address1']) && $_REQUEST['u_address1'] != '') {
        $updateArray['ab_add1'] = $_REQUEST['u_address1'];
    }
    if (isset($_REQUEST['u_address2']) && $_REQUEST['u_address2'] != '') {
        $updateArray['ab_add2'] = $_REQUEST['u_address2'];
    }
    if (isset($_REQUEST['u_country']) && $_REQUEST['u_country'] != '') {
        $updateArray['ab_country'] = $_REQUEST['u_country'];
    }
    if (isset($_REQUEST['u_state']) && $_REQUEST['u_state'] != '') {
        $updateArray['ab_state'] = $_REQUEST['u_state'];
    }
    if (isset($_REQUEST['u_city']) && $_REQUEST['u_city'] != '') {
        $updateArray['ab_city'] = $_REQUEST['u_city'];
    }
    if (isset($_REQUEST['u_pincode']) && $_REQUEST['u_pincode'] != '') {
        $updateArray['ab_pincode'] = $_REQUEST['u_pincode'];
    }
    if (isset($_REQUEST['u_phone']) && $_REQUEST['u_phone'] != '') {
        $updateArray['ab_phone'] = $_REQUEST['u_phone'];
    }
    $ab_id = isset($_REQUEST['ab_id']) ? $_REQUEST['ab_id'] : 0;
    $u_id = isset($_REQUEST['u_id']) ? $_REQUEST['u_id'] : 0;
    $updateArray['ab_modified'] = date('Y-m-d H:i:s');
    $updateParams = [
        'ab_id' => $ab_id,
        'ab_u_id' => $u_id
    ];

    $result = update('address_book', $updateArray, $updateParams);
    return ['status' => 1, 'message' => $l['MSG_CAUS']];
}

function placeOrder() {
    global $conn;
    global $l;
    $insertData = [];
    $cart_id = 0;
    if (isset($_REQUEST['u_id']) && $_REQUEST['u_id'] != '') {
        $userid = $_REQUEST['u_id'];
    } else {
        return ['status' => 1, 'message' => $l['MSG_UIR']];
    }
    
    $ab_params = ['ab_u_id' => $userid ];
    $select = queryOne('address_book', $ab_params);
    
    if(empty($select['ab_u_id'])){
       $result_ab= addMyAddressBook();
       if(isset($result_ab['ab_id']) && $result_ab['ab_id']!=''){
       $adid = $result_ab['ab_id'];
       }else
           return $result_ab;
    }  else {
        $adid = isset($select['ab_id']) ? $select['ab_id'] : 0;
    $insertData['order_shipping_address_id'] = $adid;
    }
        
    if (isset($_REQUEST['u_id']) && $_REQUEST['u_id'] != '') {
        $insertData['order_u_id'] = $u_id;
        
    } else {
        return ['status' => 1, 'message' => $l['MSG_LR']];
    }
    
    if (isset($_REQUEST['cart_id']) && $_REQUEST['cart_id'] != '') {
        $insertData['order_cart_id'] = $_REQUEST['cart_id'];
        $cart_id = $_REQUEST['cart_id'];
    } else {
        return ['status' => 1, 'message' => $l['MSG_PSALOPIC']];
    }
    $insertData['order_type'] = isset($_REQUEST['order_type']) ? $_REQUEST['order_type'] : 'cashondelivery';

    $addressType = isset($_REQUEST['add_type']) ? $_REQUEST['add_type'] : 'new';
    
    $insertData['order_billing_address_id'] = isset($_REQUEST['order_billing_address_id']) ? $_REQUEST['order_billing_address_id'] : '';
    //$insertData['order_shipping_address_id'] = isset($_REQUEST['order_shipping_address_id']) ? $_REQUEST['order_shipping_address_id'] : '';

    $cart_params = ['cart_id' => $cart_id];
    $cart_data = queryOne('cart', $cart_params);
    $amount = isset($cart_data['cart_grand_total']) ? $cart_data['cart_grand_total'] : 0;
    $insertData['order_amount'] = $amount;
    $insertData['order_created'] = date('Y-m-d H:i:s');
    $result = insert('order', $insertData);

    $updateArray['cart_modifide'] = date('Y-m-d H:i:s');
    $updateArray['cart_status'] = '1';
    $updateParams = [
        'cart_id' => $cart_id,
    ];
    update('cart', $updateArray, $updateParams);

    return ['status' => 0, 'message' => $l['MSG_OS'], 'order_id' => $result['ID']];
}
