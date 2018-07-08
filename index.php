<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>TinyWeatherStation</title>

	<link rel="shortcut icon" href="/favicon.ico">

	<!--Import Google Icon Font-->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

	<!--Import materialize.css-->
	<link type="text/css" rel="stylesheet" href="/materialize/css/materialize.min.css" media="screen,projection" />

	<script async defer src="https://buttons.github.io/buttons.js"></script>

	<!--	Import ajax-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	<!-- Import highcharts -->
	<script src="/highcharts/code/highstock.js"></script>
	<script src="/highcharts/code/modules/exporting.js"></script>
	<script src="/highcharts/code/modules/export-data.js"></script>
	<script src="/highcharts/code/js/themes/sand-signika.js"></script>

	<style>
		body {
			display: flex;
			min-height: 100vh;
			flex-direction: column;
		}

		main {
			flex: 1 0 auto;
		}

		.modal {
			width: 100% !important;
		}
	</style>
</head>

<body onLoad="updateData()">
	<header>
		<nav class="navbar-fixed blue lighten-1">
			<div class="nav-wrapper">
				<a class="brand-logo center" title="tinyweatherstation.com"><i class="material-icons">cloud</i></a>
				<ul class="right">
					<li>
						<a class="modal-trigger" data-target="select-loc" title="Change location..."><i class="material-icons left">edit_location</i>Change location</a>
					</li>
				</ul>
			</div>
		</nav>
	</header>

	<div id="select-loc" class="modal fade">
		<div class="modal-content">
			<h4>Choose your weather station location...</h4>
			<form action="#" onchange="updateCookie()">
				<p>
					<label>
						<input type="radio" name="location" value="los_ranchos" class="with-gap">
						<span>Los Ranchos Elementary School</span>
					</label>
				</p>
				<p>
					<label>
						<input type="radio" name="location" value="hawthorne" class="with-gap">
						<span>Hawthorne Elementary School</span>
					</label>
				</p>
				<p>
					<label>
						<input type="radio" name="location" value="bellevue" class="with-gap">
						<span>Bellevue Santa Fe Charter School</span>
					</label>
				</p>
				<p>
					<label>
						<input type="radio" name="location" value="home" class="with-gap">
						<span>Home</span>
					</label>
				</p>
				<p>
					<label>
					<input type="radio" name="location" value="home_indoor" class="with-gap">
					<span>Home (Indoor)</span>
					</label>
				</p>
			</form>
		</div>
		<div class="modal-footer">
			<a href="#!" class="modal-close waves-effect waves-light btn-flat" onclick="updateData()">select</a>
		</div>
	</div>

	<div id="modal-info" class="modal bottom-sheet">
		<div class="modal-content">
			<h4>Contact Me</h4>
			<p><a href="mailto:wesleynweisenberger@gmail.com">wesleynweisenberger@gmail.com</a></p>
			<p>1-805-270-6573</p>
		</div>
		<div class="modal-footer">
			<a href="#!" class="modal-close waves-effect waves-light btn-flat">cool</a>
		</div>
	</div>
	<div id="pressureInfoModal" class="modal">
		<div class="modal-content">
			<h4>What is pressure?</h4>
			<p><strong class="orange-text">Barometric Pressure</strong> is how many molecules are hitting each other in the air. When you have more molecules hitting each other, the pressure becomes <strong class="red-text">higher</strong>.</p>
			<p>When the pressure is <strong class="blue-text">low</strong> there is a higher chance of rain. The <strong class="blue-text">low pressure</strong> air rises and is more likely to form <strong class="blue-text tooltipped" data-position="bottom" data-tooltip="Precipitation is the same as rain.">precipitation</strong>.</p>
			<p><strong class="green-text">kPa</strong> stands for <strong class="pink-text tooltipped" data-position="bottom" data-tooltip="This means 1000 in the metric system.">kilo</strong><strong class="teal-text tooltipped" data-position="bottom" data-tooltip="Pascals is a unit of pressure. At sea level, atmospheric pressure is 101.3 pascals.">Pascals</strong>.
				This is a system of meausurement for pressure.</p>
			<p>If you've ever had a potato bag blow like in the picture, this is why.</p>
			<img src="pressure.png" style="width:400px;height:200px;">
			<img src="potato.jpg" style="width:300px;height:225px;">

		</div>
		<div class="modal-footer">
			<a href="" class="modalc-lose waves-effect waves-light btn-flat">awesome</a>
		</div>
	</div>
	</div>


	<div id="tempGraphModal" class="modal">
		<div class="modal-content">
			<div id="tempGraph" width="100%"></div>
		</div>
		<div class="modal-footer">
			<a href="" class="modal-close waves-effect waves-light btn-flat">cool</a>
		</div>
	</div>
	<div id="humidityGraphModal" class="modal">
		<div class="modal-content">
			<div id="humidityGraph"></div>
		</div>
		<div class="modal-footer">
			<a href="" class="modal-close waves-effect waves-light btn-flat">cool</a>
		</div>
	</div>
	<div id="pressureGraphModal" class="modal">
		<div class="modal-content">
			<div id="pressureGraph"></div>
		</div>
		<div class="modal-footer">
			<a href="" class="modal-close waves-effect waves-light btn-flat">cool</a>
		</div>
	</div>

	<main>
		<div class="row container">
			<div class="s12 m6">
				<div class="card indigo lighten-1">
					<div class="card-content white-text">
						<span class="card-title">Temperature</span>
						<span class="right card-title">
              <div class="switch">
              <label class="white-text">
              °F
              <input type="checkbox" id="tempSwitch" onclick="updateData()">
              <span class="lever"></span> °C
						</label>
						<i title="Open graph" class="material-icons right modal-trigger waves-effect waves-light" href="#tempGraphModal" onclick="graphWarning()">insert_chart</i>
					</div>
					</span>
					<span class="card-title" id="tempField">Loading...</span>
					<p class="orange-text" id="timeField"></p>

				</div>
			</div>
		</div>
		<div>
			<div class="card indigo lighten-1">
				<div class="card-content white-text">
					<span class="card-title">Humidity (%)</span>
					<span class="card-title right">
						<i title="Open graph" class="material-icons right modal-trigger waves-effect waves-light" href="#humidityGraphModal" onclick="graphWarning()">insert_chart</i>
					</span>
					<span class="card-title" id="humidityField">Loading...</span>
					<p class="orange-text" id="timeField1"></p>
				</div>
			</div>
		</div>
		<div>
			<div class="card indigo lighten-1">
				<div class="card-content white-text">
					<span class="card-title">Pressure (kPa)  <i class="material-icons modal-trigger waves-effect waves-light" href="#pressureInfoModal">info</i></span>
					<span class="card-title right">
						<i title="Open graph" class="material-icons right modal-trigger waves-effect waves-light" href="#pressureGraphModal" onclick="graphWarning()">insert_chart</i>
					</span>
					<span class="card-title" id="pressureField">Loading...</span>
					<p class="orange-text" id="timeField2"></p>
				</div>
			</div>
		</div>
		</div>

	</main>

	<footer class="page-footer light-blue">
		<div class="container">
			<div class="row">
				<div class="col l6 s12">
					<h5 class="white-text">Site Info</h5>
					<p class="grey-text text-lighten-4">This website is a server that displays data taken from <a href="https://www.google.com/maps/place
  /35%C2%B011'32.1%22N+120%C2%B032'34.0%22W/@35.192244,-120.5433362,19z/data=!3m1!4b1!4m6!3m5!1s0x0:0x0!7e2!8m2!3d35.1922445
  !4d-120.5427889"><u class="grey-text text-lighten-4">35°11'32.1"N 120°32'34.0"W</u></a> (My Home). Temperature, humidity, and pressure are taken every 15 minutes on a BME280 sensor and uploaded to this website.</p>
					<p><a class="github-button" href="https://github.com/wesleynw/tinyweatherstation.com" data-size="large" aria-label="Star wesleynw/weather-station on GitHub">See my project on GitHub</a></p>
				</div>
			</div>
		</div>
		<div class="footer-copyright">
			<div class="container">
				Created by Wesley Weisenberger - Version 1.70.1
				<a class="grey-text text-lighten-4 right waves-effect waves-light modal-trigger" href="#modal-info">Contact Me</a>
			</div>
		</div>
	</footer>

	<script type="text/javascript" src="/materialize/js/materialize.min.js"></script>
	<script>
		function getCookie(c_name) {
			var c_value = " " + document.cookie;
			var c_start = c_value.indexOf(" " + c_name + "=");
			if (c_start == -1) {
				c_value = null;
			} else {
				c_start = c_value.indexOf("=", c_start) + 1;
				var c_end = c_value.indexOf(";", c_start);
				if (c_end == -1) {
					c_end = c_value.length;
				}
				c_value = unescape(c_value.substring(c_start, c_end));
			}
			return c_value;
		}

		function updateCookie() {
			loc = document.querySelector('input[name="location"]:checked').value;
			var today = new Date();
			var expire = new Date();
			expire.setTime(today.getTime() + 3600000 * 24 * 365);
			document.cookie = "location=" + loc + "; expires=" + expire.toGMTString();
			updateData();
		}

		function graphWarning() {
			M.toast({
				html: 'Warning: Graphs may not work on mobile devices...'
			});
		}

		$(document).ready(function() {
			$('.modal').modal();
			$('select').formSelect();
			$('.tooltipped').tooltip();
			$('#select-loc').modal({
				complete: updateData()
			})

			updateData();
		});

		Highcharts.setOptions({
			global: {
				useUTC: false,
			}
		});

		function updateData() {
			if (getCookie('location') != null) {
				loc = getCookie('location')
			} else {
				$('#select-loc').modal('open');
			}


			if (document.getElementById("tempSwitch").checked == true) {
				$("#tempField").load('/getdata.php?loc=' + loc + '&type=temp_c');
			} else {
				$("#tempField").load('/getdata.php?loc=' + loc + '&type=temp_f');
			}

			$('#humidityField').load('/getdata.php?loc=' + loc + '&type=humidity')
			$('#pressureField').load('/getdata.php?loc=' + loc + '&type=pressure')
			$('#timeField').load('/getdata.php?loc=' + loc + '&type=time')
			$('#timeField1').load('/getdata.php?loc=' + loc + '&type=time')
			$('#timeField2').load('/getdata.php?loc=' + loc + '&type=time')

			$.getJSON('/getdata.php?loc=' + loc + '&type=all', function(json, status) {
				var temperature = [],
					humidity = [],
					pressure = [];
				$.each(json, function(i, el) {
					tmpTimestamp = new Date(el.timestamp)
					tmpTimestamp = tmpTimestamp.getTime() - (tmpTimestamp.getTimezoneOffset() * 60000)

					temperature.push({
						x: tmpTimestamp,
						y: parseFloat(el.temperature)
					})
					humidity.push({
						x: tmpTimestamp,
						y: parseFloat(el.humidity)
					})
					pressure.push({
						x: tmpTimestamp,
						y: parseFloat(el.pressure)
					})
				})

				var tempSeries = [{
					name: 'Temperature',
					data: temperature
				}]
				var humiditySeries = [{
					name: 'Humidity',
					data: humidity
				}]
				var pressureSeries = [{
					name: 'Pressure',
					data: pressure
				}]

				$('#tempGraph').highcharts('StockChart', {
					series: tempSeries,
					plotOptions: {
						series: {
							turboThreshold: 5000 //set it to a larger threshold, it is by default to 1000
						}
					},
					xAxis: {
						type: 'datetime'
					},
					yAxis: [{
						title: {
							text: 'Temperature'
						},
						labels: {
							format: '{value}°C'
						}
					}],
					title: {
						text: 'Temperature Over Time'
					},
					rangeSelector: {
						selected: 0,
						buttons: [{
							type: 'day',
							count: 1,
							text: '1d'
						}, {
							type: 'day',
							count: 7,
							text: '1w'
						}, {
							type: 'month',
							count: 1,
							text: '1m'
						}, {
							type: 'year',
							count: 1,
							text: '1y'
						}, {
							type: 'all',
							text: 'All'
						}]
					}
				})

				$('#humidityGraph').highcharts('StockChart', {
					series: humiditySeries,
					plotOptions: {
						series: {
							turboThreshold: 5000 //set it to a larger threshold, it is by default to 1000
						}
					},
					xAxis: {
						type: 'datetime',
					},
					yAxis: [{
						title: {
							text: 'Humidity'
						},
						labels: {
							format: '{value}%'
						}
					}],
					title: {
						text: 'Humidity Over Time'
					},
					rangeSelector: {
						selected: 0,
						buttons: [{
							type: 'day',
							count: 1,
							text: '1d'
						}, {
							type: 'day',
							count: 7,
							text: '1w'
						}, {
							type: 'month',
							count: 1,
							text: '1m'
						}, {
							type: 'year',
							count: 1,
							text: '1y'
						}, {
							type: 'all',
							text: 'All'
						}]
					}
				})

				$('#pressureGraph').highcharts('StockChart', {
					series: pressureSeries,
					plotOptions: {
						series: {
							turboThreshold: 5000 //set it to a larger threshold, it is by default to 1000
						}
					},
					xAxis: {
						type: 'datetime',
					},
					yAxis: [{
						title: {
							text: 'Pressure'
						},
						labels: {
							format: '{value}kPa'
						}
					}],
					title: {
						text: 'Pressure Over Time'
					},
					rangeSelector: {
						selected: 0,
						buttons: [{
							type: 'day',
							count: 1,
							text: '1d'
						}, {
							type: 'day',
							count: 7,
							text: '1w'
						}, {
							type: 'month',
							count: 1,
							text: '1m'
						}, {
							type: 'year',
							count: 1,
							text: '1y'
						}, {
							type: 'all',
							text: 'All'
						}]
					}
				})
			})




		}
		setInterval(updateData, 1000 * 60 * 1);
	</script>

</body>

</html>
