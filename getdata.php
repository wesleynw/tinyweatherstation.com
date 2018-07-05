<?php
header("content-type: application/json");

date_default_timezone_set('America/Los_Angeles');
require 'vendor/autoload.php';
use Carbon\Carbon;


$connection = new MySQLi('198.199.90.104', 'phpuser', '5357', 'weather');
if($connection->connect_error){
	die("Connection failed: " . $connection->connect_error);
}

$sql = "SELECT * FROM home ORDER BY timestamp DESC LIMIT 1";
$sqlAll = "SELECT * FROM home";
$data = $connection->query($sql)->fetch_assoc();
$result = $connection->query($sqlAll);


		switch($_GET['id']){
			case "farenheit":
				echo $data[temperature];
				break;
			case "celsius":
				echo substr(($data[temperature]-32)*0.5556, 0, 4);
				break;
			case "humidity":
				echo $data[humidity];
				break;
			case "pressure":
				echo $data[pressure];
				break;
			case "time":
				echo "Last Updated: " . Carbon::createFromFormat('Y-m-d H:i:s', $data[timestamp], 'UTC')->setTimezone('America/Los_Angeles')->diffForHumans();
				break;
			case "all":
					$results = array();
					while($data = $result->fetch_assoc()){
						array_push($results, $data);
					}
					echo json_encode($results);
					break;

		}
?>
