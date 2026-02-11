#include "esp_camera.h"
#include <WiFi.h>
#include "esp_http_server.h"

// ============ KONFIGURACJA - EDYTUJ TUTAJ ============

// WiFi
const char* ssid = "AkuKu";
const char* password = "12345678";

// WYBIERZ TRYB: 1, 2 lub 3
// 1 = ENERGOOSZCZĘDNY (QVGA 320x240, jakość 30, ~2-3 FPS, mało RAM)
// 2 = NORMALNY (VGA 640x480, jakość 20, ~5-8 FPS, średni RAM)
// 3 = HD (SVGA 800x600, jakość 12, ~3-5 FPS, więcej RAM i baterii)
#define CAMERA_MODE 1

// Opcje dodatkowe
#define ENABLE_WIFI_POWER_SAVING false  // true = oszczędza energię, wolniejsze WiFi
#define STREAM_DELAY_MS 100             // Opóźnienie między klatkami (ms)

// ============ KONIEC KONFIGURACJI ============


// Piny kamery dla XIAO ESP32S3 Sense
#define PWDN_GPIO_NUM     -1
#define RESET_GPIO_NUM    -1
#define XCLK_GPIO_NUM     10
#define SIOD_GPIO_NUM     40
#define SIOC_GPIO_NUM     39
#define Y9_GPIO_NUM       48
#define Y8_GPIO_NUM       11
#define Y7_GPIO_NUM       12
#define Y6_GPIO_NUM       14
#define Y5_GPIO_NUM       16
#define Y4_GPIO_NUM       18
#define Y3_GPIO_NUM       17
#define Y2_GPIO_NUM       15
#define VSYNC_GPIO_NUM    38
#define HREF_GPIO_NUM     47
#define PCLK_GPIO_NUM     13

httpd_handle_t stream_httpd = NULL;

// Ustawienia kamery według trybu
struct CameraSettings {
  framesize_t resolution;
  int jpeg_quality;
  int xclk_freq;
  int fb_count;
  const char* mode_name;
};

CameraSettings getCameraSettings() {
  CameraSettings settings;
  
  #if CAMERA_MODE == 1
    // ENERGOOSZCZĘDNY
    settings.resolution = FRAMESIZE_QVGA;    // 320x240
    settings.jpeg_quality = 30;              // Niska jakość
    settings.xclk_freq = 8000000;            // 8MHz - mało energii
    settings.fb_count = 1;                   // 1 bufor
    settings.mode_name = "ENERGOOSZCZEDNY (320x240)";
    
  #elif CAMERA_MODE == 2
    // NORMALNY (domyślny)
    settings.resolution = FRAMESIZE_VGA;     // 640x480
    settings.jpeg_quality = 20;              // Średnia jakość
    settings.xclk_freq = 10000000;           // 10MHz
    settings.fb_count = 2;                   // 2 bufory
    settings.mode_name = "NORMALNY (640x480)";
    
  #elif CAMERA_MODE == 3
    // HD
    settings.resolution = FRAMESIZE_SVGA;    // 800x600
    settings.jpeg_quality = 12;              // Dobra jakość
    settings.xclk_freq = 12000000;           // 12MHz - więcej mocy
    settings.fb_count = 2;                   // 2 bufory
    settings.mode_name = "HD (800x600)";
    
  #else
    #error "CAMERA_MODE musi być 1, 2 lub 3!"
  #endif
  
  return settings;
}

static esp_err_t stream_handler(httpd_req_t *req) {
  camera_fb_t * fb = NULL;
  esp_err_t res = ESP_OK;
  size_t _jpg_buf_len = 0;
  uint8_t * _jpg_buf = NULL;
  char * part_buf[64];

  res = httpd_resp_set_type(req, "multipart/x-mixed-replace;boundary=frame");
  if(res != ESP_OK) return res;

  while(true) {
    fb = esp_camera_fb_get();
    if (!fb) {
      Serial.println("Camera capture failed");
      res = ESP_FAIL;
    } else {
      _jpg_buf_len = fb->len;
      _jpg_buf = fb->buf;
    }

    if(res == ESP_OK) {
      size_t hlen = snprintf((char *)part_buf, 64, 
        "Content-Type: image/jpeg\r\nContent-Length: %u\r\n\r\n", _jpg_buf_len);
      res = httpd_resp_send_chunk(req, (const char *)part_buf, hlen);
    }
    if(res == ESP_OK) {
      res = httpd_resp_send_chunk(req, (const char *)_jpg_buf, _jpg_buf_len);
    }
    if(res == ESP_OK) {
      res = httpd_resp_send_chunk(req, "\r\n--frame\r\n", 13);
    }
    
    if(fb) {
      esp_camera_fb_return(fb);
      fb = NULL;
      _jpg_buf = NULL;
    }
    
    if(res != ESP_OK) break;
    delay(STREAM_DELAY_MS);
  }
  return res;
}

void startCameraServer() {
  httpd_config_t config = HTTPD_DEFAULT_CONFIG();
  config.server_port = 80;

  httpd_uri_t stream_uri = {
    .uri       = "/stream",
    .method    = HTTP_GET,
    .handler   = stream_handler,
    .user_ctx  = NULL
  };

  if (httpd_start(&stream_httpd, &config) == ESP_OK) {
    httpd_register_uri_handler(stream_httpd, &stream_uri);
  }
}

void setup() {
  Serial.begin(115200);
  delay(1000);
  
  // Pobierz ustawienia według wybranego trybu
  CameraSettings cam_settings = getCameraSettings();
  
  Serial.println("╔═══════════════════════════════════════╗");
  Serial.println("║   XIAO ESP32S3 Camera Streamer       ║");
  Serial.println("╚═══════════════════════════════════════╝");
  Serial.print("Tryb: ");
  Serial.println(cam_settings.mode_name);
  Serial.println();

  // KONFIGURACJA KAMERY
  camera_config_t config;
  config.ledc_channel = LEDC_CHANNEL_0;
  config.ledc_timer = LEDC_TIMER_0;
  config.pin_d0 = Y2_GPIO_NUM;
  config.pin_d1 = Y3_GPIO_NUM;
  config.pin_d2 = Y4_GPIO_NUM;
  config.pin_d3 = Y5_GPIO_NUM;
  config.pin_d4 = Y6_GPIO_NUM;
  config.pin_d5 = Y7_GPIO_NUM;
  config.pin_d6 = Y8_GPIO_NUM;
  config.pin_d7 = Y9_GPIO_NUM;
  config.pin_xclk = XCLK_GPIO_NUM;
  config.pin_pclk = PCLK_GPIO_NUM;
  config.pin_vsync = VSYNC_GPIO_NUM;
  config.pin_href = HREF_GPIO_NUM;
  config.pin_sscb_sda = SIOD_GPIO_NUM;
  config.pin_sscb_scl = SIOC_GPIO_NUM;
  config.pin_pwdn = PWDN_GPIO_NUM;
  config.pin_reset = RESET_GPIO_NUM;
  
  // Ustawienia z wybranego trybu
  config.xclk_freq_hz = cam_settings.xclk_freq;
  config.frame_size = cam_settings.resolution;
  config.jpeg_quality = cam_settings.jpeg_quality;
  config.fb_count = cam_settings.fb_count;
  
  config.pixel_format = PIXFORMAT_JPEG;
  config.grab_mode = CAMERA_GRAB_WHEN_EMPTY;
  config.fb_location = CAMERA_FB_IN_PSRAM;

  if(psramFound()) {
    config.fb_count = cam_settings.fb_count + 1;  // +1 bufor z PSRAM
    Serial.println("✅ PSRAM znaleziony!");
  }

  // Inicjalizacja kamery
  esp_err_t err = esp_camera_init(&config);
  if (err != ESP_OK) {
    Serial.printf("❌ Camera init FAILED: 0x%x\n", err);
    return;
  }
  Serial.println("✅ Kamera zainicjalizowana!");

  // WiFi
  WiFi.disconnect(true);
  delay(1000);
  WiFi.mode(WIFI_STA);
  delay(100);
  
  // Oszczędzanie energii WiFi
  if (ENABLE_WIFI_POWER_SAVING) {
    WiFi.setSleep(true);
    Serial.println("⚡ WiFi power saving WŁĄCZONE");
  } else {
    WiFi.setSleep(false);
    Serial.println("⚡ WiFi power saving WYŁĄCZONE (szybsze)");
  }
  
  WiFi.setMinSecurity(WIFI_AUTH_WEP);
  
  Serial.print("📡 Łączenie z WiFi: ");
  Serial.println(ssid);
  WiFi.begin(ssid, password);
  
  int attempts = 0;
  while (WiFi.status() != WL_CONNECTED && attempts < 20) {
    delay(500);
    Serial.print(".");
    attempts++;
    
    if (attempts == 10) {
      Serial.println("\n🔄 Ponowna próba...");
      WiFi.disconnect();
      delay(1000);
      WiFi.begin(ssid, password);
    }
  }
  
  if (WiFi.status() == WL_CONNECTED) {
    Serial.println();
    Serial.println("✅ WiFi połączone!");
    Serial.print("🌐 IP: ");
    Serial.println(WiFi.localIP());
    Serial.println("╔═══════════════════════════════════════╗");
    Serial.print("║ Stream URL: http://");
    Serial.print(WiFi.localIP());
    Serial.println("/stream");
    Serial.println("╚═══════════════════════════════════════╝");
    
    startCameraServer();
    Serial.println("✅ Serwer uruchomiony!");
  } else {
    Serial.println("\n❌ WiFi BŁĄD połączenia!");
    Serial.println("Sprawdź:");
    Serial.println("1. Poprawne SSID i hasło?");
    Serial.println("2. Router 2.4GHz (nie 5GHz)?");
    Serial.println("3. Antena podłączona?");
  }
}

void loop() {
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("⚠️ WiFi rozłączone! Ponowne łączenie...");
    WiFi.reconnect();
  }
  delay(5000);
}
