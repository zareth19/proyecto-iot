# 📡 Configuración Arduino - Sistema Piscícola

## ✅ Tu configuración actual:
- **WiFi**: Juan_g
- **IP Servidor**: 192.168.43.164
- **Sensores reales**: DS18B20 (temperatura), pH (pin 34), Turbidez (pin 35)
- **Intervalo**: 5 minutos (300000ms)

## 🔧 Librerías necesarias:
En Arduino IDE, instalar:
- `ArduinoJson` por Benoit Blanchon
- `OneWire` por Jim Studt
- `DallasTemperature` por Miles Burton

## 📋 Configuración actual en el código:
```cpp
// WiFi (ya configurado)
const char* ssid = "Juan_g";
const char* password = "zdik2016";

// Servidor Laravel (ya configurado)
const char* serverURL = "http://192.168.43.164/proyecto-iot/public/api/sensores";

// Sensores (ya configurados)
const int phPin = 34;        // Sensor pH
const int turbidityPin = 35; // Sensor turbidez
#define ONE_WIRE_BUS 4       // DS18B20 temperatura
```

## 🚀 Para activar el sistema:

### 1. **Iniciar servidor Laravel**
```bash
cd c:\xampp\htdocs\laravel\proyecto-iot
php artisan serve --host=0.0.0.0 --port=8000
```

### 2. **Probar conexión desde navegador**
Ir a: `http://192.168.43.164:8000/api/test`
Debe mostrar: `{"status":"ok","message":"API funcionando"}`

### 3. **Subir código a ESP32**
- El código ya está adaptado a tus sensores reales
- Usa tus sensores: DS18B20, pH y turbidez
- Simula los demás parámetros para completar la base de datos

### 4. **Verificar funcionamiento**
- Abrir Monitor Serie (115200 baudios)
- Debe mostrar lecturas cada 5 minutos
- Verificar que los datos lleguen al dashboard

## 🔍 Monitoreo:
```
=== ESP32 SENSORES PISCICOLA -> LARAVEL ===
Conectando a WiFi Juan_g ...
WiFi conectado
IP: 192.168.43.x
✓ Conexión con servidor OK

--- Leyendo sensores reales ---
Temperatura: 26.50°C
pH: 7.20 | Voltaje: 2.54V
Turbidez: 15.30 NTU | Voltaje: 1.85V

--- Envío a Laravel ---
JSON: {"temperatura":26.5,"ph":7.2,...}
Código HTTP: 200
✓ Datos enviados a Laravel exitosamente
```

## 📊 Datos que envía:
- **Sensores reales**: Temperatura (DS18B20), pH, Turbidez
- **Simulados**: Oxígeno, Amoniaco, Nitritos, Nitratos, Alcalinidad, Dureza, Conductividad

## ⚠️ Solución de problemas:
- **Error DS18B20**: Verificar conexión en pin 4
- **Error pH**: Verificar sensor en pin 34
- **Error turbidez**: Verificar sensor en pin 35
- **Error WiFi**: Red "Juan_g" debe estar disponible
- **Error servidor**: Laravel debe estar en 192.168.43.164:8000
- **Error 500**: Revisar `storage/logs/laravel.log`