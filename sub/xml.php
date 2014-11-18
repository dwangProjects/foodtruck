<?php

header("Content-type: text/xml;charset=utf-8");
// latitude, longitude and radius to search
$center_lat = $_GET["lat"];
$center_lng = $_GET["lng"];
$radius = $_GET["radius"];

// login to database
$host="127.0.0.1";
$username="wd";
$password="wd";
$dbname="foodtruck";

$connID=mysql_connect($host,$username,$password);

mysql_query("set names 'utf8'");

mysql_select_db($dbname,$connID);

// sql query
$query = sprintf("SELECT locationid, Applicant, FacilityType, Address, FoodItems, Status, Longitude, Latitude, ( 6371 * acos( cos( radians('%s') ) * cos( radians( Latitude ) ) * cos( radians( Longitude ) - radians('%s') ) + sin( radians('%s') ) * sin( radians( Latitude ) ) ) ) AS distance FROM `table 1` HAVING distance < '%s' ORDER BY distance",
  mysql_real_escape_string($center_lat),
  mysql_real_escape_string($center_lng),
  mysql_real_escape_string($center_lat),
  mysql_real_escape_string($radius));

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
echo "<table1>\n";

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
	echo 'distance="' . $row['distance'] . '" ';
    echo '/>';
}

echo "</table1>\n";
?>
