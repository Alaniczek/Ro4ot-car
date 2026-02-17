## 📡 INSTRUKCJA URUCHOMIENIA

### **Arduino (ESP8266)**
1. Otwórz: `sketch_feb11a/sketch_feb11a_SIMPLE.ino`
2. Zmień WiFi (SSID + PASS)
3. Załaduj do ESP
4. Serial Monitor (115200) → skopiuj IP

### **PHP (Komputer)**
1. `http://localhost/Ro4ot-car/ro4otAPP/src/`
2. Wpisz IP ESP
3. Testuj przyciski (LED ON/OFF)

---

## 🎯 JAK DZIAŁA

```
Komputer (PHP) → UDP:4210 → ESP8266
ESP8266 → HTTP GET → Komputer (ping)
```

---

## 📂 STRUKTURA

```
ro4otAPP/src/
├── index.php           (redirect)
├── index_simple.php    ✓ GŁÓWNY PLIK - UI + UDP
├── Components/         (deprecated)
└── stream.php
```

```
sketch_feb11a/
└── sketch_feb11a_SIMPLE.ino ✓ GŁÓWNY PLIK - WiFi + LED control
```

---

## ✅ CZYSTY KOD - BEZ SPAGHETTI

- Usunięty zakomentowany kod
- Uprościona logika
- Jeden plik Arduino, jeden plik PHP
- Brakненужных klас i plików

