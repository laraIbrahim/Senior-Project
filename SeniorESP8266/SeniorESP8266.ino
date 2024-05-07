#include <LiquidCrystal_I2C.h>

#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>
#include <Wire.h>
#include "MAX30102_PulseOximeter.h"

LiquidCrystal_I2C lcd(0x27, 20, 4);  // set the LCD address to 0x27 for a 16 chars and 2 line display

#define REPORTING_PERIOD_MS 200
#define lowTemp 28
#define highTemp 38

PulseOximeter pox;
uint32_t tsLastReport = 0;
double spO2 = 0.0;
int heartRate = 0;

#include <Keypad.h>

int firstDigit = 0;
int secondDigit = 0;
int thirdDigit = 0;
int fourthDigit = 0;
int keypadCounter = 1;
int id = 0;
bool idEntered = false;
String k = "";
char key = ' ';
const byte ROWS = 4;  //four rows
const byte COLS = 3;  //three columns
char keys[ROWS][COLS] = {
  { '1', '2', '3' },
  { '4', '5', '6' },
  { '7', '8', '9' },
  { '*', '0', '#' }
};
byte rowPins[ROWS] = { D3, D4, D6, D7 };  //connect to the row pinouts of the keypad
byte colPins[COLS] = { D8, D0, 10 };      //connect to the column pinouts of the keypad

Keypad keypad = Keypad(makeKeymap(keys), rowPins, colPins, ROWS, COLS);

#include <DS18B20.h>


DS18B20 ds(D5);
uint8_t address[] = { 40, 250, 31, 218, 4, 0, 0, 52 };
uint8_t selected;
float tempC;



const char* ssid = "";
const char* password = "";

//Your Domain name with URL path or IP address with path
String httpPOST = "";


// the following variables are unsigned longs because the time, measured in
// milliseconds, will quickly become a bigger number than can be stored in an int.
unsigned long lastTime = 0;
// Timer set to 10 minutes (600000)
//unsigned long timerDelay = 600000;
// Set timer to 5 seconds (5000)
unsigned long timerDelay = 5000;

void setup() {
  Serial.begin(9600);

  lcd.begin();  // initialize the lcd
  // Print a message to the LCD.
  lcd.backlight();

  WiFi.begin(ssid, password);  //connect to wifi
  Serial.println("Connecting");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());


  selected = ds.select(address);
}

void getPatientIdByKeypad() {
  // pinMode(D7, INPUT);
  keypadCounter = 1;
  key = ' ';
  lcd.setCursor(0, 0);
  lcd.print("Enter ID: ");
  Serial.println("Enter ID: ");
  while (key != '#') {
    key = keypad.getKey();

    if (key) {

      if (key >= '0' && key <= '9') {
        k = key;
        if (keypadCounter < 5) {
          switch (keypadCounter) {
            case (1):
              firstDigit = k.toInt();
              id = firstDigit;
              break;
            case (2):
              secondDigit = k.toInt();
              id = firstDigit * 10 + secondDigit;
              break;
            case (3):
              thirdDigit = k.toInt();
              id = firstDigit * 100 + secondDigit * 10 + thirdDigit;
              break;
            case (4):
              fourthDigit = k.toInt();
              id = firstDigit * 1000 + secondDigit * 100 + thirdDigit * 10 + fourthDigit;
              break;
          }
          keypadCounter++;
          lcd.setCursor(10, 0);
          lcd.print(id);
          Serial.println(id);
        }
      }
      if (key == '*') {
        switch (keypadCounter) {
          case (5):
            fourthDigit = 0;
            id = firstDigit * 100 + secondDigit * 10 + thirdDigit;
            break;
          case (4):
            thirdDigit = 0;
            id = firstDigit * 10 + secondDigit;
            break;
          case (3):
            secondDigit = 0;
            id = firstDigit;
            break;
          case (2):
            firstDigit = 0;
            id = 0;
            break;
        }
        keypadCounter--;
        if (id != 0) {
          lcd.setCursor(10, 0);
          lcd.print("    ");
          lcd.setCursor(10, 0);
          lcd.print(id);
          Serial.println(id);
        } else {
          lcd.setCursor(10, 0);
          Serial.println("    ");
          lcd.print("    ");
        }
      }
    }
    delay(0);
    yield();
  }

  lcd.setCursor(0, 0);
  lcd.print("                    ");
  lcd.setCursor(0, 0);
  lcd.print("Patient ID: ");
  lcd.setCursor(12, 0);
  lcd.print(id);
  lcd.setCursor(18, 0);
  lcd.print("OK");
  Serial.println("ID Entered");
}

void getPOXVitals() {
  if (!pox.begin()) {  // If initialization fails, print an error message and enter an infinite loop
    Serial.println("MAX30100 ERROR");
    for (;;)
      ;
  } else {
    Serial.println("INITIALIZED");  // If initialization succeeds, print a confirmation message
  }
  pox.setIRLedCurrent(MAX30102_LED_CURR_14_2MA);  // The default current for the IR LED is 50mA and is changed here to 14
  unsigned long currentMillis = millis();         // millis() It returns the number of milliseconds elapsed since it reached getPOXVitals
  unsigned long previousMillis = millis();
  int interval = 30000;                                 // Set reporting interval to 30 seconds
  while (currentMillis - previousMillis <= interval) {  // Loop to continuously update sensor readings
    // Make sure to call update as fast as possible
    pox.update();
    // long irValue = pox.getHeartRate();
    // Asynchronously dump heart rate and oxidation levels to the serial
    // For both, a value of 0 means "invalid"
    if (millis() - tsLastReport > REPORTING_PERIOD_MS) {
      spO2 = pox.getSpO2();
      heartRate = pox.getHeartRate();
      Serial.print("Heart rate:");
      Serial.print(heartRate);
      Serial.print("bpm / SpO2:");
      Serial.print(spO2);
      Serial.print("%");
      Serial.println();

      lcd.setCursor(0, 1);
      lcd.print("spO2: ");
      lcd.setCursor(6, 1);
      lcd.print("           ");
      lcd.setCursor(6, 1);
      lcd.print(spO2);
      lcd.print("%");
      lcd.setCursor(0, 2);
      lcd.print("BPM:  ");
      lcd.setCursor(6, 2);
      lcd.print("           ");
      lcd.setCursor(6, 2);
      lcd.print(heartRate);
      lcd.setCursor(18, 1);
      lcd.print("OK");
      lcd.setCursor(18, 2);
      lcd.print("OK");

      tsLastReport = millis();
    }
    currentMillis = millis();
    yield();
  }
}

void getTempVitals() {  //reading the temperature from a temperature sensor, displaying it on an LCD screen, and printing it to the serial monitor
  lcd.setCursor(0, 3);
  lcd.print("Temp: ");
  Serial.println("Reading temperature");

  tempC = ds.getTempC();
  Serial.print(tempC);
  Serial.println(" C");
  lcd.setCursor(6, 3);
  lcd.print(tempC);
  lcd.print(" C");
  lcd.setCursor(18, 3);
  lcd.print("OK");

  Serial.println("Temperature Reading Done");
  Serial.print("Temperature: ");
  Serial.println(tempC);
}

void checkVitalsAndActivateBuzzer() {  //monitor the vital signs of a patient and activate a buzzer if any of the vital signs fall outside certain predefined ranges.
  if (tempC < 34 || tempC > 38 || spO2 < 90 || heartRate < 60 || heartRate > 100) {
    Serial.print("Temp: ");
    Serial.print(tempC);
    Serial.print(" SPO2: ");
    Serial.print(spO2);
    Serial.print(" Heart Rate: ");
    Serial.println(heartRate);
    pinMode(D7, OUTPUT);
    digitalWrite(D7, HIGH);  //turn buzzer on
    Serial.println("Buzzer On");
    delay(2000);            //2 seconds
    digitalWrite(D7, LOW);  //turn buzzer off
    Serial.println("Buzzer Off");
    delay(2000);
    digitalWrite(D7, HIGH);  //turn buzzer on
    Serial.println("Buzzer On");
    delay(2000);            //2 seconds
    digitalWrite(D7, LOW);  //turn buzzer off
    Serial.println("Buzzer Off");
    delay(2000);
    digitalWrite(D7, HIGH);  //turn buzzer on
    Serial.println("Buzzer On");
    delay(2000);            //2 seconds
    digitalWrite(D7, LOW);  //turn buzzer off
    Serial.println("Buzzer Off");
  }
}

void insertPatientVitals() {  // this function handles the process of sending patient vitals data to the server via an HTTP POST request,
  Serial.println("Inserting Data");
  if (WiFi.status() == WL_CONNECTED) {  // Check if WiFi is connected
                                        // If WiFi is connected, proceed with sending data
    WiFiClient client;
    HTTPClient http;

    httpPOST = "http://192.168.0.104/senior_project_git/senior/insert_vitals.php?id=";
    httpPOST += String(id);
    httpPOST += "&oxygen_level=";
    httpPOST += String(spO2);
    httpPOST += "&heart_rate=";
    httpPOST += String(heartRate);
    httpPOST += "&body_temp=";
    httpPOST += String(tempC);
    // Your Domain name with URL path or IP address with path // Begin HTTP request to the server
    http.begin(client, httpPOST);

    // Data to send with HTTP POST
    http.addHeader("Content-Type", "text/html");
    String httpRequestData = "";
    // Send HTTP POST request
    int httpResponseCode = http.POST(httpRequestData);

    Serial.print("HTTP Response code: ");  // Print HTTP response code to serial monitor
    Serial.println(httpResponseCode);

    // Free resources after completing HTTP request
    http.end();
  } else {
    Serial.println("WiFi Disconnected");  // If WiFi is not connected, print a message to the serial monitor
  }
}

void loop() {
  if (id == 0) {
    lcd.clear();
    getPatientIdByKeypad();
  }

  getPOXVitals();
  getTempVitals();
  checkVitalsAndActivateBuzzer();
  insertPatientVitals();
}