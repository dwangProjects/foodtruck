<?php

header("Content-type: text/xml;charset=utf-8");

// user input searching information
$idnum = $_GET["idnum"];
$add1 = $_GET["add1"];
$add2 = $_GET["add2"];
$name = $_GET["name"];
$type = $_GET["type"];
$status = $_GET["status"];
$fooditem = $_GET["fooditem"];


// login to database
$host="127.0.0.1";
$username="wd";
$password="wd";
$dbname="foodtruck";

$connID=mysql_connect($host,$username,$password);

mysql_query("set names 'utf8'");

mysql_select_db($dbname,$connID);

// auxiliary function
function add_and($query){
    static $flag = 0;
    if (!$flag) {
        $flag = 1;
        return $query;
    }
    return $query . " AND";
}

// sql query
$query = "SELECT locationid, Applicant, FacilityType, Address, FoodItems, Status, Longitude, Latitude FROM `table 1` where ";


if ($idnum){
    $query = add_and($query);
    $query = sprintf("%s locationid like '%s'",$query, $idnum);
}

if ($name){
    $query = add_and($query);
    $query = sprintf("%s Applicant like '%s'",$query, $name);
}
if ($type){
    $query = add_and($query);
    $query = sprintf("%s FacilityType like '%s'",$query, $type);
}
if ($add1){
    $query = add_and($query);
    $query = sprintf("%s Address like '%s'",$query, $add1);
}
if ($fooditem){
    $query = add_and($query);
    $query = sprintf("%s FoodItems like '%s'",$query, $fooditem);
}
if ($status){
    $query = add_and($query);
    $query = sprintf("%s Status like '%s'",$query, $status);
}

// query result
$result = mysql_query($query);

if (!$result) {
    die('Invalid query: ' . mysql_error());
}

// function to replace the special character
function xmlencode($tag)
{
$tag = str_replace("&", "&amp;", $tag);
$tag = str_replace("<", "&lt;", $tag);
$tag = str_replace(">", "&gt;", $tag);
$tag = str_replace("'", "&apos;", $tag);
$tag = str_replace("\"", "&quot;", $tag);
return $tag;
}

// organize the results to xml file
echo '<table1>';

while ($row = @mysql_fetch_assoc($result)){
    
    echo '<table1 ';
    echo 'idnum="' . $row['locationid'] . '" ';
    echo 'name="' . xmlencode($row['Applicant']) . '" ';
    echo 'type="' . $row['FacilityType'] . '" ';
    echo 'add1="' . $row['Address'] . '" ';
    echo 'fooditem="' . xmlencode($row['FoodItems']) . '" ';
    echo 'status="' . $row['Status'] . '" ';
    echo 'lng="' . $row['Longitude'] . '" ';
    echo 'lat="' . $row['Latitude'] . '" ';
    echo '/>';
}

echo '</table1>';
?>
