#include <ESP8266WiFi.h>
#include <WiFiUdp.h>

// --- KONFIGURACJA ---
const char* WIFI_SSID = "AkuKu";
const char* WIFI_PASS = "12345678";
const char* SERVER_IP = "192.168.125.112"; // IP Twojego komputera (serwera PHP)
const int SERVER_PORT = 80;
const int LOCAL_PORT  = 4210;

WiFiUDP udp;
WiFiClient client;

void setup() {
  Serial.begin(115200);
  pinMode(LED_BUILTIN, OUTPUT);
  
  WiFi.begin(WIFI_SSID, WIFI_PASS);
  while (WiFi.status() != WL_CONNECTED) delay(500);
  
  udp.begin(LOCAL_PORT);
  Serial.println("\n=== START ESP ===");
  Serial.print("Moje IP: ");
  Serial.println(WiFi.localIP());
  
  wyslijDoPHP("Start_Systemu");
}

void loop() {
  // 1. ODBIÓR UDP (Sterowanie)
  if (udp.parsePacket()) {
    char cmd = udp.read();
    Serial.print("[UDP] Odebrano komende: ");
    Serial.println(cmd);
    
    if (cmd == '1') {
      digitalWrite(LED_BUILTIN, LOW);
      wyslijDoPHP("LED_WLACZONY");
    } 
    else if (cmd == '0') {
      digitalWrite(LED_BUILTIN, HIGH);
      wyslijDoPHP("LED_WYLACZONY");
    }
    else {
      // Zamiast spacji użyj podkreślnika, to bezpieczniejsze w URL - OD CHATA :> 
      String logMsg = "CMD_ERR_" + String(cmd); 
      wyslijDoPHP(logMsg);
    }

    // }else
    // {
    //   wyslijDoPHP("NIEZNANA_KOMENDA_" + cmd);
    // }
  }

  // 2. PING CO 10 SEKUND (Żeby nie śmiecić za mocno)
  static unsigned long ostatniCzas = 0;
  if (millis() - ostatniCzas > 10000) {
    ostatniCzas = millis();
    Serial.println("[SYSTEM] Wysylam Ping...");
    wyslijDoPHP("PING");
  }
}

// Funkcja wysyłająca logi do PHP
void wyslijDoPHP(String wiadomosc) {
  if (client.connect(SERVER_IP, SERVER_PORT)) {
    // Wysyłamy i spadamy (nie czekamy na odpowiedź)
    client.print("GET /Ro4ot-car/ro4otAPP/src/index.php?msg=" + wiadomosc + " HTTP/1.1\r\n" +
                 "Host: " + SERVER_IP + "\r\n" + 
                 "Connection: close\r\n\r\n");
    delay(30);
    client.stop();
  } else {
    Serial.println("[BLAD] Nie polaczono z PHP!");
  }
}