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
	$arrrayResult[] = $row;
}









// $sql = "SELECT * FROM home ORDER BY timestamp DESC LIMIT 1";
// $sqlAll = "SELECT * FROM home";
// $data = $connection->query($sql)->fetch_assoc();
// $result = $connection->query($sqlAll);


		// switch($_GET['id']){
		// 	case "farenheit":
		// 		echo $data[temperature];
		// 		break;
		// 	case "celsius":
		// 		echo substr(($data[temperature]-32)*0.5556, 0, 4);
		// 		break;
		// 	case "humidity":
		// 		echo $data[humidity];
		// 		break;
		// 	case "pressure":
		// 		echo $data[pressure];
		// 		break;
		// 	case "time":
		// 		echo "Last Updated: " . Carbon::createFromFormat('Y-m-d H:i:s', $data[timestamp], 'UTC')->setTimezone('America/Los_Angeles')->diffForHumans();
		// 		break;
		// 	case "all":
		// 			$results = array();
		// 			while($data = $result->fetch_assoc()){
		// 				array_push($results, $data);
		// 			}
		// 			echo json_encode($results);
		// 			break;
		//
		// }

?>
