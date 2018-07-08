#include <Wire.h>
//#include <Adafruit_Sensor.h>
#include <Adafruit_MCP9808.h>

#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <MySQL_Connection.h>
#include <MySQL_Cursor.h>

//db credentials
IPAddress server_addr(178,128,152,62); //ip of server
char user[] = "...";
char password[] = "...";

//wifi credentials
char ssid[] = "Hogwarts Great Hall Wifi";
char pass[] = "you'reawizardharry1440";

char INSERT_SQL[] = "INSERT INTO weather.home_indoor (`timestamp`, `temperature`) VALUES (CURRENT_TIMESTAMP, '%s');";
char query[128];
char temperature[10];

WiFiClient client;
MySQL_Connection conn((Client *)&client);
Adafruit_MCP9808 mcp;

void setup() {
  pinMode(LED_BUILTIN, OUTPUT);
  digitalWrite(LED_BUILTIN, LOW);
  
  Serial.begin(115200);
  Serial.println("ESP8266 Online");
  int counter = 0;
  
  //Start BME280 sensor
  Serial.println("Looking for MCP9808 sensor...");
  bool status = mcp.begin();
  if(!status){
    delay(500);
    Serial.print(".");
    counter++;
    if(counter > 50){
      ESP.restart();
    }
  }
  Serial.println("MCP9808 sensor connected");

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
  dtostrf(mcp.readTempC()*9.0/5.0 + 32, 5, 2, temperature);
  sprintf(query, INSERT_SQL, temperature);
  cur_mem->execute(query);
  delete cur_mem;
  Serial.println("Data recorded");
  digitalWrite(LED_BUILTIN, HIGH);

  ESP.deepSleep(15 * 6E7);
}

void loop(){ }
