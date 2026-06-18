#include "esp_camera.h"
#include <WiFi.h>
#include <HTTPClient.h>
#include <base64.h>
#include <SPI.h>
#include <MFRC522.h>
#include <ArduinoJson.h>

// --- KONFIGURASI WI-FI & SERVER API ---
const char* ssid       = "GITA Private";
const char* password   = "fauzan30";
const char* server_url = "http://192.168.88.156/api/v1/absensi";

// --- IDENTITAS PERANGKAT (ubah sesuai ruangan: R1, R2, R3, ...) ---
const char* device_id       = "R3";
const char* heartbeat_url   = "http://192.168.88.156/api/v1/heartbeat";
const char* firmware_version = "1.0.0";

const unsigned long HEARTBEAT_INTERVAL = 60000UL; // kirim setiap 60 detik
unsigned long lastHeartbeatMs = 0;

// --- DEFINISI PIN RFID RC522 (SPI KUSTOM) ---
#define rfid_ss_pin    2
#define rfid_rst_pin  45
#define rfid_sck_pin   1
#define rfid_mosi_pin  3
#define rfid_miso_pin 46

MFRC522 rfid(rfid_ss_pin, rfid_rst_pin);

// --- SUSUNAN PIN KAMERA ESP32-S3 WROOM CAM ---
#define PWDN_GPIO_NUM   -1
#define RESET_GPIO_NUM  -1
#define XCLK_GPIO_NUM   15
#define SIOD_GPIO_NUM    4
#define SIOC_GPIO_NUM    5
#define Y9_GPIO_NUM     16
#define Y8_GPIO_NUM     17
#define Y7_GPIO_NUM     18
#define Y6_GPIO_NUM     12
#define Y5_GPIO_NUM     10
#define Y4_GPIO_NUM      8
#define Y3_GPIO_NUM      9
#define Y2_GPIO_NUM     11
#define VSYNC_GPIO_NUM   6
#define HREF_GPIO_NUM    7
#define PCLK_GPIO_NUM   13

// --- Cetak garis pemisah ---
void printLine() {
  Serial.println("======================================");
}

// --- Parse & tampilkan response JSON dari server ---
void tampilkanRespon(int httpCode, const String& responseBody) {
  printLine();
  Serial.println("       RESPON SERVER");
  printLine();

  // Kartu tidak dikenal (404) atau error lain sebelum parse
  if (httpCode == 404) {
    Serial.println("  STATUS  : KARTU TIDAK DIKENAL");
    Serial.println("  PESAN   : Kartu belum terdaftar di sistem");
    printLine();
    return;
  }

  // Parse JSON
  JsonDocument doc;
  DeserializationError err = deserializeJson(doc, responseBody);

  if (err) {
    // Gagal parse – tampilkan raw response
    Serial.printf("  HTTP    : %d\n", httpCode);
    Serial.println("  RESPON  : " + responseBody);
    printLine();
    return;
  }

  bool    success = doc["success"] | false;
  String  status  = doc["status"]  | "unknown";
  String  pesan   = doc["message"] | "-";

  if (success) {
    // Normalkan status ke huruf besar untuk Serial Monitor
    status.toUpperCase();
    Serial.printf("  STATUS  : %s\n",  status.c_str());
    Serial.printf("  PESAN   : %s\n",  pesan.c_str());

    JsonObject data = doc["data"];
    if (!data.isNull()) {
      // Nama & kelas siswa
      if (data["siswa"]["nama"].is<const char*>()) {
        Serial.printf("  NAMA    : %s\n", (const char*)data["siswa"]["nama"]);
      }
      if (data["siswa"]["kelas"].is<const char*>()) {
        Serial.printf("  KELAS   : %s\n", (const char*)data["siswa"]["kelas"]);
      }

      // Waktu
      if (data["tanggal"].is<const char*>()) {
        Serial.printf("  TANGGAL : %s\n", (const char*)data["tanggal"]);
      }
      if (data["waktu_masuk"].is<const char*>()) {
        Serial.printf("  MASUK   : %s\n", (const char*)data["waktu_masuk"]);
      }
      if (data["waktu_keluar"].is<const char*>()) {
        Serial.printf("  KELUAR  : %s\n", (const char*)data["waktu_keluar"]);
      }
      if (data["durasi"].is<const char*>()) {
        Serial.printf("  DURASI  : %s jam\n", (const char*)data["durasi"]);
      }
    }

  } else {
    // Gagal / ditolak server
    status.toUpperCase();
    Serial.printf("  STATUS  : %s\n", status.c_str());
    Serial.printf("  PESAN   : %s\n", pesan.c_str());
    Serial.printf("  HTTP    : %d\n", httpCode);
  }

  printLine();
}

// --- Kirim heartbeat ke server ---
void sendHeartbeat() {
  if (WiFi.status() != WL_CONNECTED) return;

  WiFiClient client;
  HTTPClient http;
  http.begin(client, heartbeat_url);
  http.addHeader("Content-Type", "application/json");
  http.addHeader("Accept",       "application/json");
  http.setTimeout(5000);

  String ip   = WiFi.localIP().toString();
  String body = "{\"device_id\":\"" + String(device_id)
              + "\",\"ip\":\""       + ip
              + "\",\"firmware_version\":\"" + String(firmware_version) + "\"}";

  int code = http.POST(body);
  http.end();

  Serial.printf("[HB] device=%s ip=%s → HTTP %d\n", device_id, ip.c_str(), code);
}

void setup() {
  Serial.begin(115200);
  delay(2000);

  printLine();
  Serial.println("   SISTEM ABSENSI RFID + KAMERA");
  printLine();

  // 1. Inisialisasi Bus SPI Kustom & RFID
  SPI.begin(rfid_sck_pin, rfid_miso_pin, rfid_mosi_pin, rfid_ss_pin);
  rfid.PCD_Init();
  Serial.println("[OK] Modul RFID RC522 Siap.");

  // 2. Konfigurasi Kamera (Resolusi XGA)
  camera_config_t config;
  config.ledc_channel  = LEDC_CHANNEL_0;
  config.ledc_timer    = LEDC_TIMER_0;
  config.pin_d0        = Y2_GPIO_NUM;
  config.pin_d1        = Y3_GPIO_NUM;
  config.pin_d2        = Y4_GPIO_NUM;
  config.pin_d3        = Y5_GPIO_NUM;
  config.pin_d4        = Y6_GPIO_NUM;
  config.pin_d5        = Y7_GPIO_NUM;
  config.pin_d6        = Y8_GPIO_NUM;
  config.pin_d7        = Y9_GPIO_NUM;
  config.pin_xclk      = XCLK_GPIO_NUM;
  config.pin_pclk      = PCLK_GPIO_NUM;
  config.pin_vsync     = VSYNC_GPIO_NUM;
  config.pin_href      = HREF_GPIO_NUM;
  config.pin_sccb_sda  = SIOD_GPIO_NUM;
  config.pin_sccb_scl  = SIOC_GPIO_NUM;
  config.pin_pwdn      = PWDN_GPIO_NUM;
  config.pin_reset     = RESET_GPIO_NUM;
  config.xclk_freq_hz  = 20000000;
  config.pixel_format  = PIXFORMAT_JPEG;
  config.frame_size    = FRAMESIZE_XGA;
  config.jpeg_quality  = 12;
  config.fb_count      = 2;

  if (esp_camera_init(&config) != ESP_OK) {
    Serial.println("[ERROR] Gagal menginisialisasi kamera!");
    while (1) { delay(1000); }
  }
  Serial.println("[OK] Kamera Siap.");

  // 3. Menghubungkan ke Wi-Fi
  Serial.printf("[..] Menghubungkan ke WiFi: %s\n", ssid);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.printf("\n[OK] WiFi Terhubung! IP: %s\n", WiFi.localIP().toString().c_str());

  // Heartbeat pertama segera setelah WiFi tersambung
  sendHeartbeat();
  lastHeartbeatMs = millis();

  printLine();
  Serial.println("  Silakan tap kartu RFID...");
}

void loop() {
  // Heartbeat periodik (non-blocking)
  if (millis() - lastHeartbeatMs >= HEARTBEAT_INTERVAL) {
    lastHeartbeatMs = millis();
    sendHeartbeat();
  }

  if (!rfid.PICC_IsNewCardPresent()) return;
  if (!rfid.PICC_ReadCardSerial())   return;

  // 1. Baca UID kartu RFID
  String rfid_uid = "";
  for (byte i = 0; i < rfid.uid.size; i++) {
    if (rfid.uid.uidByte[i] < 0x10) rfid_uid += "0";
    rfid_uid += String(rfid.uid.uidByte[i], HEX);
  }
  rfid_uid.toUpperCase();

  printLine();
  Serial.printf("  KARTU TERDETEKSI\n");
  Serial.printf("  UID: %s\n", rfid_uid.c_str());

  // 2. Ambil Foto
  Serial.println("[..] Mengambil foto...");
  camera_fb_t* fb = esp_camera_fb_get();
  if (!fb) {
    Serial.println("[ERROR] Gagal mengambil foto!");
    rfid.PICC_HaltA();
    return;
  }
  Serial.printf("[OK] Foto berhasil (%u bytes)\n", fb->len);

  // 3. Encode ke Base64
  String gambarBase64 = base64::encode(fb->buf, fb->len);
  esp_camera_fb_return(fb);

  // 4. Susun JSON payload
  String jsonBody = "{\"rfid_uid\":\"" + rfid_uid
                  + "\",\"image_data\":\"" + gambarBase64
                  + "\",\"device_id\":\"" + String(device_id) + "\"}";
  Serial.printf("[OK] Ukuran payload: %u bytes | Heap bebas: %u bytes\n", jsonBody.length(), ESP.getFreeHeap());

  // 5. Kirim ke API
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("[ERROR] WiFi terputus! Tidak dapat mengirim data.");
    rfid.PICC_HaltA();
    delay(2000);
    return;
  }

  Serial.println("[..] Mengirim data ke server...");
  WiFiClient client;
  HTTPClient http;
  http.begin(client, server_url);
  http.addHeader("Content-Type", "application/json");
  http.addHeader("Accept", "application/json");

  int httpCode = http.POST(jsonBody);

  if (httpCode > 0) {
    String responseBody = http.getString();
    tampilkanRespon(httpCode, responseBody);
  } else {
    printLine();
    Serial.println("  STATUS  : GAGAL KONEKSI");
    Serial.printf("  ERROR   : %s\n", http.errorToString(httpCode).c_str());
    printLine();
  }

  http.end();
  rfid.PICC_HaltA();

  Serial.println("  Menunggu tap kartu berikutnya...\n");
  delay(2000);
}
