#include <LiquidCrystal_I2C.h>

#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>
#include <Wire.h>
#include "MAX30102_PulseOximeter.h"

LiquidCrystal_I2C lcd(0x27,20,4);  // set the LCD address to 0x27 for a 16 chars and 2 line display

#define REPORTING_PERIOD_MS     200

PulseOximeter pox;
uint32_t tsLastReport = 0;
int poxCounter = 0;
double spO2 = 0.0;
int heartRate = 0;
int tempCounter = 0;

#include <Keypad.h>

int firstDigit = 0;
int secondDigit = 0;
int thirdDigit = 0;
int fourthDigit = 0;
int keypadCounter = 1;
int id = 0;
bool idEntered = false;
String k="";
char key = ' ';
const byte ROWS = 4; //four rows
const byte COLS = 3; //three columns
char keys[ROWS][COLS] = {
  {'1','2','3'},
  {'4','5','6'},
  {'7','8','9'},
  {'*','0','#'}
};
byte rowPins[ROWS] = {D3, D4, D6, D7}; //connect to the row pinouts of the keypad
byte colPins[COLS] = {D8, D0, 10}; //connect to the column pinouts of the keypad

Keypad keypad = Keypad( makeKeymap(keys), rowPins, colPins, ROWS, COLS );

#include <DS18B20.h>


DS18B20 ds(D5);
uint8_t address[] = {40, 250, 31, 218, 4, 0, 0, 52};
uint8_t selected;
float tempC;



const char* ssid = "Rayan";
const char* password = "rayan123";

//Your Domain name with URL path or IP address with path
String httpPOST = "";

unsigned long lastTime = 0;
unsigned long timerDelay = 5000;

void setup(){
  Serial.begin(9600);

    lcd.begin();                      // initialize the lcd 
  // Print a message to the LCD.
  lcd.backlight();

    WiFi.begin(ssid, password);
    Serial.println("Connecting");
    while(WiFi.status() != WL_CONNECTED) {
      delay(500);
      Serial.print(".");
    }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());

  
selected = ds.select(address);

}
  
void loop(){
  lcd.clear();
  poxCounter = 0;
  keypadCounter = 1;
  tempCounter = 0;
  key = ' ';
  lcd.setCursor(0,0);
  lcd.print("Enter ID: ");
  lcd.setCursor(10,0);
  Serial.println("Enter ID: ");
  while(key != '#')
  {
  key = keypad.getKey();
  
  if (key){
    
    if(key >= '0' && key <= '9')
    {
      k = key;
      if(keypadCounter<5)
      {
      switch(keypadCounter)
      {
        case(1):
          firstDigit = k.toInt();
          id = firstDigit;
          break;
        case(2):
          secondDigit = k.toInt();
          id = firstDigit*10 + secondDigit;
          break;
        case(3):
          thirdDigit = k.toInt();
          id = firstDigit*100 + secondDigit*10 + thirdDigit;
          break;
        case(4):
          fourthDigit = k.toInt();
          id = firstDigit*1000 + secondDigit*100 + thirdDigit*10 + fourthDigit;
          break;
      }
      keypadCounter++;
      lcd.setCursor(10,0);
      Serial.println(id);
      lcd.print(id);
      }
    }
        if(key == '*')
    {
      switch(keypadCounter)
      {
        case(5):
          fourthDigit = 0;
          id = firstDigit*100 + secondDigit*10 + thirdDigit;
          break;
        case(4):
          thirdDigit = 0;
          id = firstDigit*10 + secondDigit;
          break;
        case(3):
          secondDigit = 0;
          id = firstDigit;
          break;
        case(2):
          firstDigit = 0;
          id = 0;
          break;
      }
      keypadCounter--;
      if(id!=0)
      {
      lcd.setCursor(10,0);
      lcd.print("    ");
      lcd.setCursor(10,0);
      lcd.print(id);
      Serial.println(id);
      }
      else
      {
      lcd.setCursor(10,0);
      Serial.println("    ");
      lcd.print("    ");
      }
    }
  }
  delay(0);
  }
  lcd.setCursor(18,0);
  lcd.print("OK");
  lcd.setCursor(0,1);
  Serial.println("ID Entered");
  lcd.print("Reading spO2");
  lcd.setCursor(0,1);
  delay(1000);
  lcd.print("             ");
  lcd.setCursor(0,1);
  

if (!pox.begin()) {
        Serial.println("MAX30102 ERROR");
        for(;;);
    } else {
        Serial.println("INITIALIZED");
    }
    // The default current for the IR LED is 50mA and is changed below
    pox.setIRLedCurrent(MAX30102_LED_CURR_7_6MA);

  while(poxCounter < 40000)
  {
    // Make sure to call update as fast as possible
    pox.update();
   // long irValue = pox.getHeartRate();
    // Asynchronously dump heart rate and oxidation levels to the serial
    // For both, a value of 0 means "invalid"
    if (millis() - tsLastReport > REPORTING_PERIOD_MS) {
        Serial.print("Heart rate:");
        Serial.print(pox.getHeartRate());
        Serial.print("bpm / SpO2:");
        Serial.print(pox.getSpO2());
        Serial.print("%");
        Serial.println();

        lcd.setCursor(0,1);
        lcd.print("spO2: ");
        lcd.print(pox.getSpO2());
        lcd.print("%");
        lcd.setCursor(0,2);
        lcd.print("BPM:  "); 
        lcd.print(pox.getHeartRate());

        tsLastReport = millis();
    }
    poxCounter++;
    yield();
  }

  Serial.println("spO2 Reading Done");
  spO2 = pox.getSpO2();
  heartRate = pox.getHeartRate();
  Serial.print("Heart rate:");
  Serial.print(heartRate);
  Serial.print("bpm / SpO2:");
  Serial.print(spO2);
  Serial.print("%");

  Serial.println();

  lcd.setCursor(0,1);
  lcd.print("spO2: ");
  lcd.print(pox.getSpO2());
  lcd.print("%");
  lcd.setCursor(0,2);
  lcd.print("BPM:  ");      
  lcd.print(pox.getHeartRate());
  lcd.setCursor(18,1);
  lcd.print("OK");
  lcd.setCursor(18,2);
  lcd.print("OK");
  lcd.setCursor(0,3);
  lcd.print("Reading temperature");
  Serial.println("Reading temperature");
  delay(5000);
  
  tempC = ds.getTempC();
  Serial.print(tempC);
  Serial.println(" C");
  lcd.print(tempC);
  lcd.print(" C");
  lcd.setCursor(18,3);
  lcd.print("OK");
  delay(1000);

  Serial.println("Temperature Reading Done");
  Serial.print("Temperature: ");
  Serial.println(tempC);

  if(WiFi.status()== WL_CONNECTED){
      WiFiClient client;
      HTTPClient http;
      
      httpPOST = "http://172.20.10.3/hms/insert_vitals.php?id=";
      httpPOST += String(id);
      httpPOST += "&oxygen_level=";
      httpPOST += String(spO2);
      httpPOST += "&heart_rate=";
      httpPOST += String(heartRate);
      httpPOST += "&body_temp=";
      httpPOST += String(tempC);
      // Your Domain name with URL path or IP address with path
      http.begin(client, httpPOST);
  
      // Data to send with HTTP POST
      http.addHeader("Content-Type", "text/html");
      String httpRequestData = "";           
      // Send HTTP POST request
      int httpResponseCode = http.POST(httpRequestData);
     
      Serial.print("HTTP Response code: ");
      Serial.println(httpResponseCode);
        
      // Free resources
      http.end();
    }
    else {
      Serial.println("WiFi Disconnected");
  }


}