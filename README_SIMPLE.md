# 🚗 ESP8266 - Instrukcja

## Arduino Setup

1. Otwórz: `sketch_feb11a/sketch_feb11a_SIMPLE.ino`
2. Zmień WiFi (`SSID` + `PASS`) na Twoje dane
3. Upload do ESP8266
4. Serial Monitor (115200 baud) → skopiuj IP

## Komputer

Przejdź na:
```
http://localhost/Ro4ot-car/ro4otAPP/src/
```

Wpisz IP ESP z Serial Monitora → testuj przyciski

---

## Struktura (Nowa)

```
ro4otAPP/src/
├── index.php          ← GŁÓWNY PLIK
├── EspClient.php      ← Wysyłanie UDP  
├── PingLog.php        ← Logowanie pingów
└── [reszta - deprecated]
```

```
sketch_feb11a/
└── sketch_feb11a_SIMPLE.ino ← GŁÓWNY PLIK
```

---

## Jak Działa

**Komputer** → UDP na :4210 → **ESP8266** (LED control)  
**ESP8266** → HTTP GET co 3s → **Komputer** (ping)

---

## Kod

✅ Объектово (klasy: `EspClient`, `PingLog`)  
✅ Prosty UI (bez grafik, bez CSS)  
✅ Bez zbędnego zamieszania  
✅ Czytelny i gotowy do rozszerzenia
