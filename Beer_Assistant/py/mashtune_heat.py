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
interval = 2    # sec waiting
heat = 0        # if set to 1, controller will activate heating
mashing = 0     # if set to 0, controller will not perform mashing

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
    print ("Found active batch with id: %s", id)
    mashing = 1
 
def getTemp():
    #temp_c = random.randint(0,100)
    temperature = sensor.get_temperature()
    return round(temperature, 1)
 
while(mashing):  
    print "Checking ending time"
    sql = ("""SELECT ending_time FROM batch WHERE id=%s""", (id, ))
    cur.execute(*sql)
    rows = cur.fetchall()
    
    for row in rows:
        print ("Found results: %s", row)
        
        if(row[0] is None): 
            temp = getTemp()
            print temp

            # Checking current temperature (single step)
            # ****** Add multimple steps
            sql = ("""SELECT target_temp FROM mashing_step WHERE id=%s""", (id, ))
            cur.execute(*sql)
            steps = cur.fetchall()
            for step in steps:
                if (temp<step[0]):
                    heat = 1
                else:
                    heat = 0

            # Managing heat element
            if(heat):
                print "*** Activating heating element"
                GPIO.output(pinHeat, GPIO.LOW)
            else:
                print "*** Deactivating heating element"
                GPIO.output(pinHeat, GPIO.HIGH)
            
            
            sql = ("""INSERT INTO temp_mashing (timestamp, id, temperature, heated) VALUES (CURRENT_TIMESTAMP,%s,%s,%s)""",(id,temp,heat))
            
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
            mashing = 0
        

cur.close()
db.close()

print "Cleaning up GPIO"
GPIO.cleanup()