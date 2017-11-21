import RPi.GPIO as GPIO
import time

GPIO.setmode(GPIO.BCM)
pinList = [19, 20, 21, 26]

#sleep(0.5)

# loop through pins and set mode and state to 'low'
for i in pinList: 
    GPIO.setup(i, GPIO.OUT) 
    GPIO.output(i, GPIO.LOW)
    

sleep(2)
GPIO.cleanup()