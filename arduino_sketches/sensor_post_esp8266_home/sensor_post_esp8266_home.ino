#include <Wire.h>
#include <Adafruit_Sensor.h>
#include <Adafruit_BME280.h>

#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <MySQL_Connection.h>
#include <MySQL_Cursor.h>

//db credentials
IPAddress server_addr(178,128,152,62); //ip of server
char user[] = "...";
char password[] = "...";

//wifi credentials
char ssid[] = "...";
char pass[] = "...";

char INSERT_SQL[] = "INSERT INTO weather.home (`timestamp`, `temperature`, `humidity`, `pressure`) VALUES (CURRENT_TIMESTAMP, '%s', '%s', '%s');";
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
  Serial.println("Looking for BME280 sensor...");
  bool status = bme.begin();
  if(!status){
    delay(500);
    Serial.print(".");
    counter++;
    if(counter > 50){
      ESP.restart();
    }
  }
  Serial.println("BME280 sensor connected");

  counter = 0;
  //Start WiFi
  Serial.print("Connecting to WiFi...");
  WiFi.begin(ssid, pass);
  while (WiFi.status() != WL_CONNECTED ) {
    delay (500);
    Serial.print (".");
    counter++;
    if(counter > 50){
      ESP.restart();
    }
  }
  Serial.println("");
  Serial.print("Connected to ");
  Serial.println(ssid);
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());

  counter = 0;
  Serial.print("Connecting to DB...");
  while (conn.connect(server_addr, 3306, user, password) != true) {
    delay(500);
    Serial.print(".");
    counter++;
    if(counter > 50){
      ESP.restart();
    }
  }

  MySQL_Cursor *cur_mem = new MySQL_Cursor(&conn);
  dtostrf(bme.readTemperature()*1.8+32, 5, 2, temperature);
  dtostrf(bme.readHumidity(), 5, 2, humidity);
  dtostrf(bme.readPressure()/1000, 5, 2, pressure);
  sprintf(query, INSERT_SQL, temperature, humidity, pressure);
  cur_mem->execute(query);
  delete cur_mem;
  Serial.println("Data recorded");
  digitalWrite(LED_BUILTIN, HIGH);

  ESP.deepSleep(15 * 6E7);
}

void loop(){ }



