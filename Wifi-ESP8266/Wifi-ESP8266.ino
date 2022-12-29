// WifiClientBasic.ino

#include <ESP8266WiFi.h>
#include <ESP8266WiFiMulti.h>
#include <ArduinoJson.h>
#include "FirebaseESP8266.h"
#include "DHT.h"

ESP8266WiFiMulti WiFiMulti;
// Use WiFiClient class to create TCP connections
WiFiClient client;
// firebase
#define FIREBASE_HOST "https://fir-d98dc-default-rtdb.firebaseio.com"
#define FIREBASE_AUTH "ZJbmXncsmuWnZlU73DDuTzlZvRV1wHeBQ862t6hg"
// wifi
#define ssid "Live4_2386" // Thay đổi tên wifi của bạn
#define password "00000000" // Thay đổi password wifi của bạn
//raspberry
#define host "homecare.hopto.org" // ip address of raspberry
#define port 8888         // port open of server in raspberry
//light
#define LIGHT_PIN 4
#define DEVICE_NAME "Toilet Light"
#define IdDevice "L2"
#define INIT_IdDevice "INIT:"IdDevice

#define DHTPIN 14    // Chân dữ liệu của DHT 11 , với NodeMCU chân D5 GPIO là 14
#define DHTTYPE DHT11   // DHT 11
DHT dht(DHTPIN, DHTTYPE);

bool SentID();
bool Switch(bool set);

FirebaseData firebaseData;
String path = "/";
FirebaseJson json;

void setup_Firebase(){
  Firebase.begin(FIREBASE_HOST, FIREBASE_AUTH);
  Firebase.reconnectWiFi(true);
  if (!Firebase.beginStream(firebaseData, path)){
    Serial.print("REASON: " + firebaseData.errorReason());
    Serial.println();
  }
}
void setup() {
    
    pinMode(LED_BUILTIN, OUTPUT);  
    Serial.begin(115200);
    delay(10);

    // We start by connecting to a WiFi network
    WiFiMulti.addAP(ssid, password);

    Serial.println();
    Serial.println();
    Serial.print("Wait for WiFi... ");

    while(WiFiMulti.run() != WL_CONNECTED) {
        Serial.print(".");
        delay(500);
    }

    Serial.println("");
    Serial.println("WiFi connected");
    Serial.println("IP address: ");
    Serial.println(WiFi.localIP());

    pinMode(LIGHT_PIN, OUTPUT);
    delay(500);
    setup_Firebase();
}

void loop() {

    Serial.print("connecting to ");
    Serial.println(host);
    if (!client.connect(host, port)) {
        Serial.println("connection failed");
        Serial.println("wait 5 sec...");
        delay(5000);
        return;
    }
    while(!SentID()); // wait until set successful
    while(1){
        float h = dht.readHumidity();
        float t = dht.readTemperature();
        Serial.print("Nhiet do: ");
        Serial.print(t);
        Serial.print("*C  ");
        Serial.print("Do am: ");
        Serial.print(h);
        Serial.println("%  ");
        Firebase.setFloat (firebaseData, path + "/Temp", t);
        Firebase.setFloat (firebaseData, path + "/Humidity", h);
        delay(300);
        // Read all the lines of the reply from server and print them to Serial
        while(client.available()) {
            String line = client.readStringUntil('\r');
            Serial.println(line);
            if(line.equals("state=1")){
              Firebase.setBool (firebaseData, path + "/Light State", true);
              Switch(true);              
            }

            else if(line.equals("state=0")){
              Firebase.setBool (firebaseData, path + "/Light State", false);
              Switch(false);              
            }
        }
        if(WiFiMulti.run() != WL_CONNECTED){
          break;
        }
    }
    client.stop();
}

bool SentID() {
  Serial.print("Send identify: "); Serial.println(DEVICE_NAME);
  client.print(INIT_IdDevice);
  delay(500);
  while(client.available()) {
      String line = client.readStringUntil('\r');
      if(line.equals("OK")) {
          Serial.println("the device was successful setted");
          return true;
      }
  }
  return false;
}

bool Switch(bool set) {
  Serial.print("Switch is ");
  if(set) {
  Serial.println("on !");
  digitalWrite(LED_BUILTIN, LOW);
  }
  else{
  Serial.println("off !");
  digitalWrite(LED_BUILTIN, HIGH);
  }
  return true;
}
