<?php
    // Disable error reporting on screen
    error_reporting(0);

    // Helper functions
    function print_error($value, $message) {
        $array = array('error' => $value, 'message' => $message);
        $json = json_encode($array);

        echo $json;
    }

    function print_data($id, $data) {
        $array = array('error' => 0, 'id' => $id, 'data' => $data);
        $json = json_encode($array);

        echo $json;
    }

    function print_id($id) {
        $array = array('error' => 0, 'id' => $id);
        $json = json_encode($array);

        echo $json;
    }

    function check_error($ret, $object) {
        if (!$ret) {
            print_error($object->errono, $object->error);

            exit();
        }
    }

    function check_key($key, $array) {
        if (!array_key_exists($key, $array)) {
            print_error(1, $key . " does not provided");

            exit();
        }
    }

    // Connection definitions
    $hostname = "172.28.0.4";
    $portname = 3306;
    $username = getenv('MYSQL_USER'); 
    $password = getenv('MYSQL_PASSWORD');
    $database = getenv('MYSQL_DATABASE');

    // Create MYSQLi connection
    $mysqli = new mysqli($hostname, $username, $password, $database, $portname);

    if ($mysqli->connect_errno) {
        print_error($mysqli->connect_errno, $mysqli->connect_error);

        exit();
    }

    // Simple REST server
    $method = $_SERVER["REQUEST_METHOD"];
    $override = $_SERVER["HTTP_X_HTTP_METHOD_OVERRIDE"];
    $body = file_get_contents('php://input');

    // Override if X-HTTP-Method-Override is set
    if (mb_strlen($override)) {
        $method = $override;
    }

    // Parse JSON data
    $json = json_decode($body, true);

    if (json_last_error()) {
        print_error(1, json_last_error_msg());

        exit();
    }

    if ($method == "GET") {
        check_key("id", $json);

        $id = $json["id"];

        if (!is_numeric($id)) {
            print_error(1, "Invalid id is provided");
        }
        else {
            $query = $mysqli->prepare("select * from table_test where id = ?");
            check_error($query, $mysqli);

            $ret = $query->bind_param('i', $id);
            check_error($ret, $query);

            $ret = $query->execute();
            check_error($ret, $query);

            $query->bind_result($id, $data);
            $ret = $query->fetch();

            if ($ret) {
                print_data($id, $data);
            }
            else {
                print_error(1, "Data does not exists");
            }

            $query->close();
        }
    }
    elseif ($method == "POST") {
        check_key("data", $json);

        $data = $json["data"];

        if (mb_strlen($data) == 0) {
            print_error(1, "No data is provided");
        }
        else {
            $query = $mysqli->prepare("insert into table_test (`data`) values (?)");
            check_error($query, $mysqli);

            $ret = $query->bind_param('s', $data);
            check_error($ret, $query);

            $ret = $query->execute();
            check_error($ret, $query);

            print_id($query->insert_id);

            $query->close();
        }
    }
    elseif ($method == "PUT") {
        check_key("id", $json);
        check_key("data", $json);

        $id = $json["id"];
        $data = $json["data"];

        if (!is_numeric($id) || mb_strlen($data) == 0) {
            print_error(1, "Invalid id or no data is provided");
        }
        else {
            $query = $mysqli->prepare("update table_test set data = ? where is = ?");
            check_error($query, $mysqli);

            $ret = $query->bind_param('si', $data, $id);
            check_error($ret, $query);

            $ret = $query->execute();
            check_error($ret, $query);

            if ($query->affected_rows == 0) {
                print_error(1, "Not updated");
            }
            else {
                echo "{\"error\":0}";
            }

            $query->close();
        }
    }
    elseif ($method == "DELETE") {
        check_key("id", $json);

        $id = $json["id"];

        if (!is_numeric($id)) {
            print_error(1, "Invalid id is provided");
        }
        else {
            $query = $mysqli->prepare("delete from table_test where id = ?");
            check_error($query, $mysqli);

            $ret = $query->bind_param('i', $id);
            check_error($ret, $query);

            $ret = $query->execute();
            check_error($ret, $query);

            if ($query->affected_rows == 0) {
                print_error(1, "Not deleted");
            }
            else {
                echo "{\"error\":0}";
            }

            $query->close();
        }
    }
    elseif ($method == "OPTIONS") {
        // Assumes CORS preflight
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Method: POST');
        header('Access-Control-Allow-Headers: Content-Type, X-HTTP-Method-Override');

        echo "{\"error\":0}";
    }
    else {
        print_error(1, "Unknown HTTP verb");
    }

    // Close MYSQLi connection
    $mysqli->close();
?>
