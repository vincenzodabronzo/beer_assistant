'''
Created on 8 Nov 2017

@author: kenzo
'''

import MySQLdb
import time
from w1thermsensor import W1ThermSensor 
#from _mysql import NULL
import RPi.GPIO as GPIO

pinHeat = 26    # GPIO pin connected to heat Relay
interval = 1    # sec waiting
heat = 0        # if set to 1, controller will activate heating

GPIO.setmode(GPIO.BCM)
pinList = [19, 20, 21, 26]
# loop through pins and set mode and state to 'low'
for i in pinList: 
    GPIO.setup(i, GPIO.OUT) 
    GPIO.output(i, GPIO.HIGH)

# Variables for MySQL
db = MySQLdb.connect(host="localhost", user="pi", passwd="raspberry", db="dbeer")
cur = db.cursor()

# Initializing Sensor GPIO (4)
sensor = W1ThermSensor()

# Checking last active batch
sql = ("""SELECT id FROM batch WHERE ending_time is NULL""")
cur.execute(sql,)
rows = cur.fetchall()
for row in rows:
    id = row[0]
    print "Found active batch with id:"
    print id
    heat = 1

if(heat):
    print "Activating heating element"
    GPIO.output(pinHeat, GPIO.LOW)
 
def getTemp():
    #temp_c = random.randint(0,100)
    temperature = sensor.get_temperature()
    return round(temperature, 1)
 
while(heat):  
    print "Checking ending time"
    sql = ("""SELECT ending_time FROM batch WHERE id=%s""", (id, ))
    cur.execute(*sql)
    rows = cur.fetchall()
    
    for row in rows:
        print "Found results:"
        print row
        
        if(row[0] is None): 
            temp = getTemp()
            print temp
            sql = ("""INSERT INTO temp_mashing (timestamp, id, temperature) VALUES (CURRENT_TIMESTAMP,%s,%s)""",(id,temp))
            
            try:
                print "Writing to database..."
                # Execute the SQL command
                cur.execute(*sql)
                # Commit your changes in the database
                db.commit()
                print "Write OK"
            except:
                # Rollback in case there is any error
                db.rollback()
                print "Failed writing to database"
        
            time.sleep(interval)
        else:
            heat = 0


cur.close()
db.close()

print "Deactivating heating element and cleaning up"
GPIO.output(pinHeat, GPIO.LOW)
GPIO.cleanup()