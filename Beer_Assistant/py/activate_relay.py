import init

#sleep(0.5)

# loop through pins and set mode and state to 'low'
for i in pinList: 
    GPIO.setup(i, GPIO.OUT) 
    GPIO.output(i, GPIO.LOW)
    

time.sleep(2)
GPIO.cleanup()