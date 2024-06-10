#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <SoftwareSerial.h>
#include <ArduinoJson.h>
#include <LiquidCrystal_I2C.h>
#include <Wire.h>

const char* ssid = "eh aripla";
const char* password = "danish1312";
const char* serverAddress = "172.20.10.14/AirGuardian_new";
const String uploadPath = "/adddata.php";
const String getStatusPath = "/fetch_device_status.php";
const String getNumbersPath = "/get_n.php"; 

LiquidCrystal_I2C lcd(0x27, 16, 2);

#define SIM800_TX_PIN D2
#define SIM800_RX_PIN D3
#define RELAY_PIN 2
#define buzzer D1

SoftwareSerial sim800l(SIM800_TX_PIN, SIM800_RX_PIN);

void setup() {
    Wire.begin(D6, D5);
    lcd.begin();
    lcd.backlight();
    Serial.begin(115200);
    sim800l.begin(9600);  // Use 9600 baud rate for GSM module

    Serial.println("Serial communication initialized.");

    // Check if the GSM module responds to AT commands
    if (!sendATCommand("AT", "OK")) {
        Serial.println("GSM module not responding to AT commands");
        while (true);  // Stop execution
    }
    
    // Set SMS text mode
    if (!sendATCommand("AT+CMGF=1", "OK")) {
        Serial.println("Failed to set SMS text mode");
        while (true);  // Stop execution
    }
    
    // Test SIM card presence
    if (!sendATCommand("AT+CPIN?", "+CPIN: READY")) {
        Serial.println("SIM card not ready");
        while (true);  // Stop execution
    }

    WiFi.begin(ssid, password);
    while (WiFi.status() != WL_CONNECTED) {
        delay(500);
        Serial.println("Connecting to Wi-Fi...");
    }
    Serial.println("Wi-Fi connected.");
    
    pinMode(RELAY_PIN, OUTPUT);
    pinMode(buzzer, OUTPUT);
    Serial.println("Relay pin configured as output.");
}

void loop() {
    String url = "http://" + String(serverAddress) + getStatusPath;
    WiFiClient c;
    c.setTimeout(1000);
    HTTPClient http;
    http.begin(c, url);
    int httpResponseCode = http.GET();

    if (httpResponseCode == 200) {
        Serial.println(httpResponseCode);
        String responseStr = http.getString();
        int response = responseStr.toInt();

        if (response == 1) {
            Serial.println("Relay On");
            digitalWrite(RELAY_PIN, HIGH);

            while (response == 1) {
                if (Serial.available()) {
                    Serial.println("Ready To Receive");
                    String sensorData = Serial.readStringUntil('\n');
                    Serial.println(sensorData);

                    if (sensorData.indexOf("MQ135:") != -1) {
                        // Extract MQ135 sensor data
                        int mq135Value = sensorData.substring(sensorData.indexOf("MQ135:") + 6).toInt();
                        lcd.setCursor(0, 0);
                        lcd.print("AQI: ");
                        lcd.print(mq135Value);
                        lcd.print("%");
                        delay(1000);

                        // Check if the data contains all MQ sensors
                        bool dataComplete = (sensorData.indexOf("MQ5:") != -1) && (sensorData.indexOf("MQ6:") != -1) &&
                                            (sensorData.indexOf("MQ7:") != -1) && (sensorData.indexOf("MQ135:") != -1);
                        if (dataComplete) {
                            // Sanitize and extract all MQ sensor values
                            int mq5Value = sensorData.substring(sensorData.indexOf("MQ5:") + 4, sensorData.indexOf(", MQ6:")).toInt();
                            int mq6Value = sensorData.substring(sensorData.indexOf("MQ6:") + 4, sensorData.indexOf(", MQ7:")).toInt();
                            int mq7Value = sensorData.substring(sensorData.indexOf("MQ7:") + 4, sensorData.indexOf(", MQ135:")).toInt();

                            // Submit sanitized data to the database
                            uploadSensorData(mq5Value, mq6Value, mq7Value, mq135Value);
                        }
                    } else {
                        Serial.println("Incomplete or invalid sensor data");
                    }
                }

                delay(1000);
                http.begin(c, url);
                httpResponseCode = http.GET();
                if (httpResponseCode == 200) {
                    responseStr = http.getString();
                    response = responseStr.toInt();
                } else {
                    Serial.printf("Error fetching status: %d\n", httpResponseCode);
                    response = 0;
                }
                http.end();
            }
        } else if (response == 0) {
            digitalWrite(RELAY_PIN, LOW);
            Serial.println("Relay Off");
        } else {
            Serial.println("Invalid response");
        }
    } else {
        Serial.printf("Error fetching status: %d\n", httpResponseCode);
    }

    http.end();        
    delay(5000);
}

void uploadSensorData(int mq5Value, int mq6Value, int mq7Value, int mq135Value) {
    String url = "http://" + String(serverAddress) + uploadPath + "?";
    url += "mq5_value=" + String(mq5Value);
    url += "&mq6_value=" + String(mq6Value);
    url += "&mq7_value=" + String(mq7Value);
    url += "&mq135_value=" + String(mq135Value);

    WiFiClient client;
    HTTPClient http;

    if (http.begin(client, url)) {
        int httpResponseCode = http.GET();
        if (httpResponseCode == HTTP_CODE_OK) {
            Serial.println("Sensor data uploaded successfully");
        } else {
            Serial.print("Error uploading sensor data. HTTP error code: ");
            Serial.println(httpResponseCode);
        }
        http.end();
    } else {
        Serial.println("Failed to connect to server for uploading sensor data");
    }
}

int getPhoneNumbersFromServer(String phoneNumbers[], int maxNumbers) {
    String url = "http://" + String(serverAddress) + getNumbersPath;
    WiFiClient c;
    HTTPClient http;
    http.begin(c, url);
    int httpResponseCode = http.GET();

    if (httpResponseCode > 0) {
        String payload = http.getString();
        http.end();

        // Parse the JSON response
        DynamicJsonDocument doc(1024);
        deserializeJson(doc, payload);
        JsonArray arr = doc.as<JsonArray>();

        int count = 0;
        for (JsonVariant v : arr) {
            if (count < maxNumbers) {
                phoneNumbers[count++] = v.as<String>();
            } else {
                break;
            }
        }
        return count;
    } else {
        http.end();
        return 0;
    }
}

void SendMessage(String number) {
    if (!sendATCommand("AT+CMGF=1", "OK")) {
        Serial.println("Failed to set SMS text mode");
        return;
    }

    String atCommand = "AT+CMGS=\"" + number + "\"";
    if (!sendATCommand(atCommand.c_str(), ">")) {
        Serial.println("Failed to set recipient number");
        return;
    }

    String SMS = "Alert. Gas Detected Status: Danger!";
    sim800l.print(SMS);
    delay(100);

    sim800l.write(26);  // CTRL+Z to send the message

    String response = _readSerial();
    Serial.println("Response from GSM module: " + response);
}

bool sendATCommand(const char* command, const char* expectedResponse, unsigned long timeout = 5000) {
    sim800l.println(command);
    Serial.print("Sending command: ");
    Serial.println(command);

    unsigned long startTime = millis();
    String response = "";

    while (millis() - startTime < timeout) {
        while (sim800l.available()) {
            char c = sim800l.read();
            response += c;
        }
        if (response.indexOf(expectedResponse) != -1) {
            Serial.println("Expected response received: " + response);
            return true;
        }
    }
    
    Serial.println("Timeout or unexpected response: " + response);
    return false;
}

String _readSerial() {
    String response = "";
    long int time = millis();
    while ((time + 5000) > millis()) {
        while (sim800l.available()) {
            char c = sim800l.read();
            response += c;
        }
    }
    return response;
}
