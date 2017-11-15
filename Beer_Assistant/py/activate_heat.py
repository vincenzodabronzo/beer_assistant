import RPi.GPIO as GPIO

GPIO.setmode(GPIO.BCM)

# loop through pins and set mode and state to 'low'

GPIO.output(17, GPIO.HIGH)
GPIO.output(27, GPIO.HIGH)