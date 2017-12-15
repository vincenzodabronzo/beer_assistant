import MySQLdb
import time
from w1thermsensor import W1ThermSensor 
import RPi.GPIO as GPIO
# Checking single instance
import singleton
me = singleton.SingleInstance()

# Initializing GPIO
GPIO.setmode(GPIO.BCM)
pinList = [20, 21]
# loop through pins and set mode and state to 'low'
for i in pinList: 
    GPIO.setup(i, GPIO.OUT) 
    GPIO.output(i, GPIO.HIGH)
    
# Initializing Sensor GPIO (4)
for sensor in W1ThermSensor.get_available_sensors([W1ThermSensor.THERM_SENSOR_DS18B20]):
    print("Sensor %s has temperature %.2f" % (sensor.id, sensor.get_temperature()))

print "Cleaning up GPIO"
GPIO.cleanup()