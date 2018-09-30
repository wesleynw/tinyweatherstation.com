#include <Wire.h>
#include <Adafruit_Sensor.h>
#include <Adafruit_BME280.h>

#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <MySQL_Connection.h>
#include <MySQL_Cursor.h>

//db credentials
IPAddress server_addr(1,1,1,1); //ip of server
char user[] = "homeESP";
char password[] = "5357";

//wifi credentials
char ssid[] = "...";
char pass[] = "...";

char INSERT_SQL[] = "INSERT INTO \\\./// (`timestamp`, `temperature`, `humidity`, `pressure`) VALUES (CURRENT_TIMESTAMP, '%s', '%s', '%s');";
char query[128];
char temperature[10];
char humidity[10];
char pressure[10];

WiFiClient client;
MySQL_Connection conn((Client *)&client);
Adafruit_BME280 bme;

void setup() {
  pinMode(LED_BUILTIN, OUTPUT);
  digitalWrite(LED_BUILTIN, LOW);

  Serial.begin(115200);
  Serial.println("ESP8266 Online");
  int counter = 0;

  //Start BME280 sensor
  Serial.println("Looking for BME280 sensor");
  bool status = bme.begin();
  if(!status){
    delay(500);
    Serial.print(".");
    counter++;
    if(counter > 10){
      Serial.println('ESP Restarting')
      ESP.restart();
    }
  }
  Serial.println("BME280 sensor connected");

  //Start WiFi
  Serial.print("Connecting to WiFi");
  counter = 0;
  WiFi.begin(ssid, pass);
  while (WiFi.status() != WL_CONNECTED ) {
    delay (500);
    Serial.print (".");
    counter++;
    if(counter > 20){
      Serial.println('ESP Restarting')
      ESP.restart();
    }
  }
  Serial.println("");
  Serial.print("Connected to ");
  Serial.println(ssid);
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());

  counter = 0;
  Serial.print("Connecting to DB");
  while (conn.connect(server_addr, 3306, user, password) != true) {
    delay(500);
    Serial.print(".");
    counter++;
    if(counter > 15){
      Serial.println('ESP Restarting')
      ESP.restart();
    }
  }

  //Connect to db
  counter = 0;
  MySQL_Cursor *cur_mem = new MySQL_Cursor(&conn);
  dtostrf(bme.readTemperature()*1.8+32, 5, 2, temperature);
  dtostrf(bme.readHumidity(), 5, 2, humidity);
  dtostrf(bme.readPressure()/1000, 5, 2, pressure);
  sprintf(query, INSERT_SQL, temperature, humidity, pressure);

  while(true) {
    cur_mem->execute(query);
    counter++;
    if(counter > 15){
      Serial.println('ESP Restarting')
      ESP.restart();
    }
  }
  delete cur_mem;
  Serial.println("Data recorded");
  digitalWrite(LED_BUILTIN, HIGH);

  ESP.deepSleep(15 * 6E7);
}

void loop(){ }
