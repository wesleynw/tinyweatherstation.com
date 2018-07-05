<?php
header("content-type: application/json");

date_default_timezone_set('America/Los_Angeles');
require 'vendor/autoload.php';
use Carbon\Carbon;

//this mysql user is granted only select privileges, you may use it to fetch data if you would like...
$connection = new MySQLi('178.128.152.62', 'webuser', '1234', 'weather');
if($connection->connect_error){
	die("Connection failed: " . $connection->connect_error);
}

$query = "SELECT * FROM ". $_GET['loc'] . ";";
$result = $connection->query($query);

while($row = $result->fetch_assoc()){
	$arrayResult[] = $row;
}

switch ($_GET['type']) {
	case 'temp_f':
		echo end($arrayResult)[temperature];
		break;
	case 'temp_c':
		echo substr((end($arrayResult)[temperature]-32)*0.5556, 0, 4);
		break;
	case 'humidity':
		echo end($arrayResult)[humidity];
		break;
	case 'pressure':
		echo end($arrayResult)[pressure];
		break;
	case "time":
		echo "Last Updated: " . Carbon::createFromFormat('Y-m-d H:i:s', $data[timestamp], 'UTC')->setTimezone('America/Los_Angeles')->diffForHumans();
		break;
	case "all":
		echo json_encode($arrrayResult);
	default:
		echo "error";
		break;
}

?>
