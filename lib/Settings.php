<?php

error_reporting(0);

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', 'girnar');
define('DB_DATABASE', 'ecommerce');

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if (!$con) {
    die("Opps some thing went wrong");
} else {
    mysql_select_db(DB_DATABASE, $con);
}

/*
 * get results from query
 * @param string $query required
 * @author Rajneesh Singh <rajneesh.hlm@gmail.com
 */

function getResult($query) {
    $query = mysql_query($query);
    $new_array = '';
    while ($row = mysql_fetch_assoc($query)) {
        $new_array[] = $row;
    }
    return $new_array;
}

define('ES_HOST', 'localhost');
define('ES_PORT', 9200);
/*
 * get results from query 
 * @param string $index required
 * @param string $type required
 * @param string $queryString required
 * @param string $requeryType required (GET, PUT, POST, DELETE, HEAD)
 * @param json $jsonDoc optional
 * @author Rajneesh Singh <rajneesh.hlm@gmail.com>
 */

function esCurlCall($index, $type, $queryString, $requeryType, $jsonDoc = '') {
    $url = 'http://' . ES_HOST . ':' . ES_PORT . '/' . $index . '/' . $type . '/' . $queryString;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_PORT, ES_PORT);
    curl_setopt($ch, CURLOPT_TIMEOUT, 200);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, 0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $requeryType);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDoc);
    $response = curl_exec($ch);
    return $response;
}

function categoryListSelect($id_parent = 0, $space = '') {
    $q = "SELECT * FROM category WHERE id_parent = '" . $id_parent . "' ";
    $r = mysql_query($q) or die(mysql_error());

    $count = mysql_num_rows($r);

    if ($id_parent == 0) {
        $space = '';
    } else {
        $space .="&nbsp;-&nbsp;";
    }
    if ($count > 0) {

        while ($row = mysql_fetch_array($r)) {
            $cid = $row['id'];
            echo "<option value=" . $cid . ">" . $space . $row['name'] . "</option>";

            categoryListSelect($cid, $space);
        }
    }
}

function recursiveDelete($id) {
    $result=mysql_query("SELECT * FROM category WHERE id_parent='$id'");
    if (mysql_num_rows($result)>0) {
         while($current=mysql_fetch_array($result)) {
              recursiveDelete($current['id']);
         }
    }
    mysql_query("DELETE FROM category WHERE id='$id'");
}
?>