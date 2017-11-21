import RPi.GPIO as GPIO

GPIO.setmode(GPIO.BCM)

pinList = [19, 20, 21, 26]

GPIO.setup(17, GPIO.OUT) 
GPIO.output(17, GPIO.HIGH)
GPIO.setup(27, GPIO.OUT) 
GPIO.output(27, GPIO.HIGH)

#sleep(0.5)

# loop through pins and set mode and state to 'low'
for i in pinList: 
    GPIO.setup(i, GPIO.OUT) 
    GPIO.output(i, GPIO.HIGH)