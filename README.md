# Weather Station

### This is a project for my Eagle Scout Project
I will install weather stations at various elementary schools around San Luis Obispo, California.

## How it works
Each weather station contains a tiny micro-controller called an [Feather Huzzah](https://www.adafruit.com/product/2821) which contains a chip for WiFi called an ESP8266.

Every 15 minutes, the Feather switches on a [BME280 Sensor](https://www.adafruit.com/product/2652) which measures temperature with ±1.0°C accuracy, humidity with ±3% accuracy, and pressure with ±1 hPa absolute accuracy.

Using software on the Feather, it connections to a server in the cloud hosted by DigitalOcean and stores it in a SQL database. This database can be accessed easily by querying [tinyweatherstation.com/getdata.php](tinyweatherstation.com/getdata.php)

Using the data stored in the databases, the website which is built in HTML and uses the [MaterializeCSS](https://materializecss.com/) library for formatting displays this information, within seconds of it being measured.

For the charts used in the website, an open-source graphing library was used called [Highcharts](https://www.highcharts.com/).

## Accessing the databases
Apart from using the website to access the data, you can use PHP to query the server to get raw data, which could be displayed anywhere.

For example:
```
https://tinyweatherstation.com/getdata.php?loc=los_ranchos&type=temp_c
```
This will grab the most recent temperature reading in Celsius from Los Ranchos.

The various location you can grab from are listed below:
```
loc=los_ranchos //Los Ranchos Elementary School
loc=hawthorne //Hawthorne Elementary School
loc=bellevue //Bellevue Santa Fe Charter School
loc=home //my house
loc=home_indoor //inside my house
```

The various datatypes you may grab from the PHP are listed below:
```
type=temp_f //temperature in Farenheit
type=temp_c //temperature in Celsius
type=humidity //humidity in percent
type=pressure //pressure in kPa
type=time //returns formatted string of how long ago the data was collected
type=all //returns a JSON document with all the values every recorded in the selected database
```
