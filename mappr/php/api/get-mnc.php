<?php

if (empty($api_auth) || $api_auth === false) die();

if (empty($_GET["mcc"]) || !is_numeric($_GET["mcc"])) {
	die();
}
$mcc = clean($_GET["mcc"]);

$sql = "SELECT DISTINCT mnc FROM " . DB_SECTORS . " WHERE mcc = '{$mcc}'";

$r = $db_connection->query($sql);

if (!$r) die();

$get_sectors = $db_connection->prepare("SELECT DISTINCT(sector_id) FROM ".DB_SECTORS." WHERE mnc = ? ORDER BY sector_id");
$get_sectors->bind_param("i",$thisMnc);

$mncData = array();
while ($row = $r->fetch_object()) {
	$thisMnc = intval($row->mnc);

	if (!$get_sectors->execute()){
		printf("Database Error: %s\n",$get_sectors->error);
		die();
	}

	$mncData[$thisMnc] = array();

	$get_sectors->bind_result($resSectorIds);
	while($get_sectors->fetch()) {
		$mncData[$thisMnc][] = $resSectorIds;
	}
}

$get_sectors->close();

die(json_encode($mncData,JSON_PRETTY_PRINT));