<?php

class General {


    public function encryptString($string) {
        return md5($string);
    }

    public function test() {
        return 'Test Function';
    }



    public function insert($tableName, $data) {
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
        $checkFields = $this->checkTableFields($tableName, $data);
        if (isset($checkFields['status']) && $checkFields['status'] == '0') {

            $insert = "INSERT INTO $tableName ($fields) VALUES($values)";
            mysql_query($insert);
            return ['status' => 0, 'message' => 'record inserted', 'ID' => mysql_insert_id()];
        } else {
            return $checkFields;
        }
    }

    public function update($tableName, $data, $params) {
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

        $checkFields = $this->checkTableFields($tableName, $data);
        if (isset($checkFields['status']) && $checkFields['status'] == '0') {
            $select = "SELECT * FROM $tableName WHERE $condition";
            $selectQuery = mysql_query($select);
            $row = mysql_fetch_assoc($selectQuery);
            if (!empty($row)) {
                $update = "UPDATE $tableName SET $updateString WHERE $condition";
                mysql_query($update);
                return['status' => 0, 'message' => 'record updated'];
            } else {
                return['status' => 1, 'message' => 'invalid params'];
            }
        } else {
            return $checkFields;
        }
    }

    function queryOne($tableName, $params) {
        $checkFields = $this->checkTableFields($tableName, $params);
        if (isset($checkFields['status']) && $checkFields['status'] == '0') {
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
            $selectQuery = mysql_query($select);
            return mysql_fetch_assoc($selectQuery);
        } else {
            return $checkFields;
        }
    }

    function delete($tableName, $params) {
        $checkFields = $this->checkTableFields($tableName, $params);
        if (isset($checkFields['status']) && $checkFields['status'] == '0') {
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
            $selectQuery = mysql_query($select);
            return ['status' => 1];
        } else {
            return $checkFields;
        }
    }

    function queryAll($tableName, $params = '') {
        $lastParams = '';
        $condition = '';
        if (!empty($params)) {
            $checkFields = $this->checkTableFields($tableName, $params);
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
            $checkFields['status'] = '0';
        }
        $data1 = [];
        if (isset($checkFields['status']) && $checkFields['status'] == '0') {
            $select = "SELECT * FROM $tableName WHERE 1=1 $condition";
            $selectQuery = mysql_query($select);
            $i = 0;
            while ($row = mysql_fetch_assoc($selectQuery)) {
                $data1[$i] = $row;
                $i++;
            }
            return $data1;
        }
    }

    function checkTableFields($tableName, $data) {
        $getColumnString = "SHOW COLUMNS FROM " . $tableName;
        $queryColumn = mysql_query($getColumnString);
        $fields = [];
        $i = 0;
        while ($rowColumn = mysql_fetch_assoc($queryColumn)) {
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
	

}
