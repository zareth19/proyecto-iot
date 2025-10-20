#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>
#include <OneWire.h>
#include <DallasTemperature.h>

// --------- CONFIG WIFI ----------
const char* ssid = "iPhone";
const char* password = "987654321";

// --------- CONFIG SERVIDOR LARAVEL ----------
const char* serverURL = "http://192.168.43.164/proyecto-iot/public/api/sensores";
const char* testURL = "http://192.168.43.164/proyecto-iot/public/api/test";

// --------- CONFIG SENSOR PH ----------
const int phPin = 34;
int analogPh = 0;
float voltagePh = 0;
float phValue = 0;

// --------- CONFIG SENSOR TURBIDEZ ----------
const int turbidityPin = 35;
int analogTurbidity = 0;
float voltageTurbidity = 0;
float turbidity = 0;

// --------- CONFIG DS18B20 ----------
#define ONE_WIRE_BUS 4
OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensors(&oneWire);

void setup() {
  Serial.begin(115200);
  delay(200);
  Serial.println("\n=== ESP32 SENSORES PISCICOLA -> LARAVEL ===");
  
  // Inicializar DS18B20
  sensors.begin();
  
  conectarWiFi();
}

void loop() {
  if (WiFi.status() != WL_CONNECTED) {
    conectarWiFi();
  }

  Serial.println("\n--- Leyendo sensores reales ---");
  
  // ---- Lectura temperatura DS18B20 ----
  sensors.requestTemperatures();
  float temperatura = sensors.getTempCByIndex(0);
  
  if (temperatura == DEVICE_DISCONNECTED_C) {
    Serial.println("Error leyendo DS18B20");
    temperatura = 25.0; // Valor por defecto
  }
  
  // ---- Lectura pH ----
  analogPh = analogRead(phPin);
  voltagePh = analogPh * (3.3 / 4095.0);
  phValue = 7 + ((voltagePh - 2.5) / 0.18);
  
  // ---- Lectura turbidez ----
  analogTurbidity = analogRead(turbidityPin);
  voltageTurbidity = analogTurbidity * (3.3 / 4095.0);
  turbidity = -1120.4 * voltageTurbidity * voltageTurbidity + 5742.3 * voltageTurbidity - 4352.9;
  if (turbidity < 0) turbidity = 0;
  
  // Mostrar valores
  mostrarValores(temperatura, phValue, turbidity);
  
  // Enviar a Laravel
  enviarDatosLaravel(temperatura, phValue, turbidity);
  
  delay(300000); // 5 minutos como en tu código original
}

void conectarWiFi() {
  WiFi.mode(WIFI_STA);
  if (WiFi.status() == WL_CONNECTED) return;

  Serial.print("Conectando a WiFi ");
  Serial.print(ssid);
  Serial.println(" ...");

  WiFi.begin(ssid, password);

  const uint8_t MAX_INTENTOS = 50;
  uint8_t intentos = 0;
  while (WiFi.status() != WL_CONNECTED && intentos < MAX_INTENTOS) {
    delay(500);
    Serial.print('.');
    intentos++;
  }

  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\nWiFi conectado");
    Serial.print("IP: ");
    Serial.println(WiFi.localIP());
    probarConexion();
  } else {
    Serial.println("\nNo se pudo conectar a WiFi. Reiniciando...");
    delay(1000);
    ESP.restart();
  }
}
//
// Generar valores simulados para sensores que no tienes
float simularOxigeno() { return 5.0 + random(-10, 20) / 10.0; }
float simularAmoniaco() { return 0.02 + random(0, 5) / 100.0; }
float simularNitritos() { return 0.1 + random(0, 10) / 100.0; }
float simularNitratos() { return 50.0 + random(-20, 40); }
float simularAlcalinidad() { return 80.0 + random(-30, 30); }
float simularDureza() { return 150.0 + random(-50, 100); }
float simularConductividad() { return 200.0 + random(-50, 100); }

void probarConexion() {
  HTTPClient http;
  http.begin(testURL);
  int httpResponseCode = http.GET();
  
  if (httpResponseCode == 200) {
    Serial.println("✓ Conexión con servidor OK");
  } else {
    Serial.println("✗ Error de conexión con servidor: " + String(httpResponseCode));
  }
  http.end();
}

void mostrarValores(float temp, float ph, float turbidez) {
  Serial.println("Temperatura: " + String(temp, 2) + "°C");
  Serial.println("pH: " + String(ph, 2) + " | Voltaje: " + String(voltagePh, 2) + "V");
  Serial.println("Turbidez: " + String(turbidez, 2) + " NTU | Voltaje: " + String(voltageTurbidity, 2) + "V");
}

void enviarDatosLaravel(float temp, float ph, float turbidez) {
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("WiFi no conectado. Cancelando envío.");
    return;
  }

  HTTPClient http;
  http.begin(serverURL);
  http.addHeader("Content-Type", "application/json");
  http.setTimeout(10000);
  
  // Crear JSON solo con sensores reales
  DynamicJsonDocument doc(512);
  doc["temperatura"] = temp;
  doc["ph"] = ph;
  doc["turbidez"] = turbidez;
  
  String jsonString;
  serializeJson(doc, jsonString);
  
  Serial.println("\n--- Envío a Laravel ---");
  Serial.println("JSON: " + jsonString);
  
  int httpCode = http.POST(jsonString);
  
  if (httpCode <= 0) {
    Serial.println("Fallo en POST. Error: " + http.errorToString(httpCode));
  } else {
    Serial.println("Código HTTP: " + String(httpCode));
    String respuesta = http.getString();
    Serial.println("Respuesta: " + respuesta);
    
    if (httpCode >= 200 && httpCode < 300) {
      Serial.println("✓ Datos enviados a Laravel exitosamente");
    } else {
      Serial.println("✗ Error del servidor Laravel");
    }
  }
  
  http.end();
  Serial.println("-----------------------------");
}