#include <ESP8266WiFi.h>
#include <WiFiUdp.h>

// --- TWOJE DANE ---
const char* SSID = "AkuKu";
const char* PASS = "12345678";
const int   PORT = 4210;
// ------------------

WiFiUDP udp;
char pakiet[255]; // Bufor na dane

void setup() {
  Serial.begin(115200);
  pinMode(LED_BUILTIN, OUTPUT);
  
  // Mrugnij 2 razy na start (test diody)
  digitalWrite(LED_BUILTIN, LOW); delay(200); digitalWrite(LED_BUILTIN, HIGH); delay(200);
  digitalWrite(LED_BUILTIN, LOW); delay(200); digitalWrite(LED_BUILTIN, HIGH);

  WiFi.begin(SSID, PASS);
  
  Serial.print("\nŁączenie z "); Serial.print(SSID);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  
  Serial.println("\n--- POŁĄCZONO! ---");
  Serial.print("Moje IP: ");
  Serial.println(WiFi.localIP()); // <--- TO IP ZAPISZ SOBIE!!!
  Serial.println("Czekam na UDP...");

  udp.begin(PORT);
}

void loop() {
  int packetSize = udp.parsePacket();
  if (packetSize) {
    // 1. Wyczyść bufor (ważne!)
    memset(pakiet, 0, 255);
    
    // 2. Odczytaj dane
    udp.read(pakiet, 255);
    char komenda = pakiet[0]; // Bierzemy pierwszy znak

    Serial.print("Odebrano: "); Serial.println(komenda);

    // 3. Wykonaj (Active LOW: LOW=Świeci, HIGH=Zgaszona)
    if (komenda == '1') {
      digitalWrite(LED_BUILTIN, LOW); 
    }
    else if (komenda == '0') {
      digitalWrite(LED_BUILTIN, HIGH);
    }
  }
}