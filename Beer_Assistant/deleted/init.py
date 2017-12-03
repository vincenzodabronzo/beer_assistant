# Set all port to LOW then cleanup

import RPi.GPIO as GPIO

GPIO.setmode(GPIO.BCM)
pinList = [19, 20, 21, 26]

# loop through pins and set mode and state to 'low'
for i in init.pinList: 
    GPIO.setup(i, GPIO.OUT) 
    GPIO.output(i, GPIO.LOW)
    
GPIO.cleanup()