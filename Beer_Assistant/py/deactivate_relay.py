import RPi.GPIO as GPIO
import init

GPIO.setmode(GPIO.BCM)

#sleep(0.5)

# loop through pins and set mode and state to 'low'
for i in init.pinList: 
    GPIO.setup(i, GPIO.OUT) 
    GPIO.output(i, GPIO.HIGH)
    
time.sleep(2)
GPIO.cleanup()