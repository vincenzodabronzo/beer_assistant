import RPi.GPIO as GPIO

GPIO.setmode(GPIO.BCM)
pinList = [19, 20, 21, 26]

#sleep(0.5)

# loop through pins and set mode and state to 'low'
for i in pinList: 
    GPIO.output(i, GPIO.LOW)