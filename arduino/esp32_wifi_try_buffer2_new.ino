#include <WiFi.h>

#include <HTTPClient.h>

#define up_freq 50 // set the uploading frequency ===> upload frequency= 1000/up_freq ===> total time to upload=  up_freq*7000
#define ac_freq 100 // 1us*acfreq = time for one sample =====> frequency = 1/(time for one sample)
#define currentOffset 0.00
#define CurrentAnalogInputPin 35 // to phase A
#define CurrentAnalogInputPin2 34 // to phase B
#define CurrentAnalogInputPin3 32 // to phase C
#define offset 0
#define bearing_case 25
/* to read the value of a sample*/

bool flag = false;
bool u_f = false;

unsigned long currentLastSample = 0;
String type = "";
String condition;
unsigned int currentSampleCount = 0;
unsigned long count = 0;
unsigned long readDataPrevMillis = 0;
const char * ssid = "Safah";
const char * password = "safah@987654";
String apiKeyValue = "tPmAT5Ab3j7F9";
String sensorName = "Current Clamp Sensor";
String sensorLocation = "3 Phase DC motor";
const char * serverName = "http://192.168.1.106/sensordata/post-esp-data.php";
float up_val[7000];
float up_val1[7000];
float up_val2[7000];

//===============================================================================

float voltage_to_current(int analog) {
  float gradient = 40.0 / 3.0;
  float intercept = -110.0 / 3.0;
  float voltages = (float(analog) / 4096.0) * 5;
  if (voltages < 0.5) voltages = 2.75;
  float current = (voltages) * gradient + intercept;
  return current - offset;

}
//=============================================================================

const uint16_t port = 80;
const char * host = "192.168.0.105";

void setup() {

  pinMode(bearing_case, INPUT_PULLUP);
  pinMode(CurrentAnalogInputPin, INPUT);
  pinMode(CurrentAnalogInputPin2, INPUT);
  pinMode(CurrentAnalogInputPin3, INPUT);

  Serial.begin(115200);
  delay(1000);

  WiFi.begin(ssid, password);
  Serial.println("\nConnecting");

  while (WiFi.status() != WL_CONNECTED) {
    Serial.print(".");
    delay(500);
  }

  Serial.println("\nConnected to the WiFi network");
  Serial.print("Local ESP32 IP: ");
  Serial.println(WiFi.localIP());
  int v;

  Serial.print("Input the type of Bearing: \n  1. bearing with lubricant  \n  2.tempered bearing \n  3.inner fault bearing \n 4.outer fault bearing \n 5.healthy bearing");
  while (!Serial.available()) delay(50);
  char menu = Serial.read();
  switch (menu) {

  case '1':
    type = "bearing_with_lubricant";
    break;
  case '2':
    type = "tempered_bearing";
    break;
  case '3':
    type = "inner_fault_bearing";
    break;
  case '4':
    type = "outer_fault_bearing";
    break;
  case '5':
    type = "healthy";
    break;

  default:
    type = "N";
    break;
  }

}

void loop() {
  if ((millis() - readDataPrevMillis > 1000 || readDataPrevMillis == 0)) {
    readDataPrevMillis = millis();
    if (digitalRead(bearing_case)) {

      condition = "extreme";
    } else {
      condition = "early";
    }
  }

  //============= for acquisition frequency=====================================
  if (micros() >= currentLastSample + ac_freq) //                                                                /* every 1 milli second taking 1 reading */
  {
    float currentSampleRead = 0;
    currentSampleRead = voltage_to_current(analogRead(CurrentAnalogInputPin)); //((moduleMiddleVoltage/moduleSupplyVoltage)*4096)      /* read the sample value */
    float phA = currentSampleRead;

    currentSampleRead = 0;
    currentSampleRead = voltage_to_current(analogRead(CurrentAnalogInputPin2)); //((moduleMiddleVoltage/moduleSupplyVoltage)*4096);      /* read the sample value */
    float phB = currentSampleRead;

    currentSampleRead = 0;
    currentSampleRead = voltage_to_current(analogRead(CurrentAnalogInputPin3)); //((moduleMiddleVoltage/moduleSupplyVoltage)*4096);      /* read the sample value */
    float phC = currentSampleRead;

    currentLastSample = micros();
    if (currentSampleCount < 7000) {

      up_val[currentSampleCount] = phA;
      up_val1[currentSampleCount] = phB;
      up_val2[currentSampleCount] = phC;

    }
    currentSampleCount++;

  }

  if (currentSampleCount == 7000) /* after 1000 count or 1000 milli seconds (1 second), do the calculation and display value*/ {

    /* calculate average value of all sample readings taken*/

    if (condition == "early") {
      for (int i = 0; i < currentSampleCount; i++) {
        if (WiFi.status() == WL_CONNECTED) {
          HTTPClient http;

          // Your Domain name with URL path or IP address with path
          http.begin(serverName);
          http.addHeader("Content-Type", "application/x-www-form-urlencoded");
          String httpRequestData = "api_key=" + apiKeyValue + "&sensor=" + sensorName +
            "&location=" + sensorLocation + "&value1=" + String(up_val[i]) +
            "&value2=" + String(up_val1[i]) + "&value3=" + String(up_val2[i]) + "";
          Serial.print("httpRequestData: ");
          Serial.println(httpRequestData);
          int httpResponseCode = http.POST(httpRequestData);

          if (httpResponseCode > 0) {
            Serial.print("HTTP Response code: ");
            Serial.println(httpResponseCode);
            Serial.println(http.getString());
          } else {
            Serial.print("Error code: ");
            Serial.println(httpResponseCode);
          }
          // Free resources
          http.end();
        }

      }
      delay(up_freq);
      flag = false;
      u_f = true;
    } else {

    }
  }
}
