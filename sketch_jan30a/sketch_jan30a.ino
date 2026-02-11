int PWMA = D1;
int PWMB = D2;
int WiRed = D6;
int WiBlk = D7;
int ZiRed = D3;
int ZiBlk = D4;

void setup() {
  Serial.begin(115200);
  analogWriteRange(255);
  
  pinMode(PWMA, OUTPUT);
  pinMode(PWMB, OUTPUT);
  pinMode(WiRed, OUTPUT);
  pinMode(WiBlk, OUTPUT);
  pinMode(ZiRed, OUTPUT);
  pinMode(ZiBlk, OUTPUT);
}

void OrderToWheels(int order, int model, int power = 200) {
  int pwmPin;
  if (model == 1) pwmPin = PWMA;
  else if (model == 2) pwmPin = PWMB;
  else return;

  if (order == 0) {
    digitalWrite(pwmPin, LOW);
    if (model == 1) {
      digitalWrite(WiRed, LOW);
      digitalWrite(WiBlk, LOW);
    } else {
      digitalWrite(ZiRed, LOW);
      digitalWrite(ZiBlk, LOW);
    }
    return;
  }

  analogWrite(pwmPin, power);

  if (model == 1) {
    digitalWrite(WiRed, (order == 1) ? HIGH : LOW);
    digitalWrite(WiBlk, (order == 1) ? LOW : HIGH);
  } 
  else if (model == 2) {
    digitalWrite(ZiRed, (order == 1) ? HIGH : LOW);
    digitalWrite(ZiBlk, (order == 1) ? LOW : HIGH);
  }
}

void loop() {
  /*for (int mocA = 100; mocA <= 300; mocA += 50) {
    Serial.print("Test B (Przod): ");
    Serial.println(mocA);
    OrderToWheels(2, 2, mocA);
    delay(2000);
  }*/
  OrderToWheels(2, 1);
  OrderToWheels(2, 2);
    delay(2000);
  OrderToWheels(0, 1);
  OrderToWheels(0, 2);
  delay(2000);
}