import RPi.GPIO as GPIO

GPIO.setmode(GPIO.BCM)

# init list with pin numbers

pinList = [17, 27]

# loop through pins and set mode and state to 'low'

GPIO.setup(17, GPIO.OUT) 
GPIO.output(17, GPIO.HIGH)

GPIO.setup(27, GPIO.OUT) 
GPIO.output(27, GPIO.HIGH)

GPIO.output(17, GPIO.LOW)
GPIO.output(27, GPIO.LOW)