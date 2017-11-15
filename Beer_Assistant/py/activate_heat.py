import RPi.GPIO as GPIO

GPIO.setmode(GPIO.BCM)

# init list with pin numbers

pinList = [17, 27]

# loop through pins and set mode and state to 'low'

#GPIO.cleanup()

GPIO.setup(17, GPIO.OUT) 
GPIO.output(17, GPIO.HIGH)
GPIO.setup(18, GPIO.OUT) 
GPIO.output(18, GPIO.HIGH)

GPIO.output(17, GPIO.LOW)
GPIO.output(18, GPIO.LOW)


#GPIO.cleanup()