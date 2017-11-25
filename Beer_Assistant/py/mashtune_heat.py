'''
Created on 8 Nov 2017

@author: kenzo

TODOS:

1 - Add multistep support (auto)
2 - 

'''


import MySQLdb
import time
from w1thermsensor import W1ThermSensor 
#from _mysql import NULL
import RPi.GPIO as GPIO

pinHeat = 26                # GPIO pin connected to heat Relay
pinPump = 19                # GPIO pin connected to pump Relay
interval = 2                # sec waiting
heat = 0                    # if set to 1, controller will activate heating
pump_recirculation = 0      # if set to 0, pump will not recirculate water
mashing = 0                 # if set to 0, controller will not perform mashing

# Initializing GPIO
GPIO.setmode(GPIO.BCM)
pinList = [19, 20, 21, 26]
# loop through pins and set mode and state to 'low'
for i in pinList: 
    GPIO.setup(i, GPIO.OUT) 
    GPIO.output(i, GPIO.HIGH)
# Initializing Sensor GPIO (4)
sensor = W1ThermSensor()


# Variables for MySQL
db = MySQLdb.connect(host="localhost", user="pi", passwd="raspberry", db="dbeer")
cur = db.cursor()
# Checking last active batch
sql = ("""SELECT ba.id, ba.name, mc.starting_time, mc.ending_time FROM mashing_config AS mc INNER JOIN batch AS ba ON mc.id = ba.id where mc.ending_time is NULL ORDER BY ba.id DESC LIMIT 1""")


cur.execute(sql,)
rows = cur.fetchall()
for row in rows:
    id = row[0]
    print "Found 1 active batch with id:"
    print id
    
    mashing = 1
 
def getTemp():
    #temp_c = random.randint(0,100)
    temperature = sensor.get_temperature()
    return round(temperature, 1)
 
while(mashing):  
    print "------------------------------------"
    sql = ("""SELECT mc.ending_time, mc.pump_recirculation, ms.target_temp FROM mashing_config AS mc INNER JOIN mashing_step AS ms ON mc.id = ms.id WHERE mc.id=%s""", (id, ))
    cur.execute(*sql)
    rows = cur.fetchall()
    
    for row in rows:
        if(row[0] is None):
            temp = getTemp()
            pump_recirculation = row[1]
            target_temp = row[2]
            
            # Checking current temperature (single step)
            if (temp<target_temp):
                heat = 1
                GPIO.output(pinHeat, GPIO.LOW)
            else:
                heat = 0
                GPIO.output(pinHeat, GPIO.HIGH)
                
            # Checking recirculation pump config
            if(pump_recirculation):
                GPIO.output(pinPump, GPIO.LOW)
            else:
                GPIO.output(pinPump, GPIO.HIGH)
            
            print "[1 Mashing opened]"
            print "Temp (Celsius)"
            print temp
            print "Target"
            print target_temp
            print "Pump"
            print pump_recirculation
            print "Heat"
            print heat
            
            sql = ("""INSERT INTO mashing_temp (timestamp, id, temperature, heated) VALUES (CURRENT_TIMESTAMP,%s,%s,%s)""",(id,temp,heat))
            
            try:
                # Execute the SQL command
                cur.execute(*sql)
                # Commit your changes in the database
                db.commit()
                print "Writing to database...OK"
            except:
                # Rollback in case there is any error
                db.rollback()
                print "*** Writing to database...ERROR ***"
            
            time.sleep(interval)
        else:
            mashing = 0
        

cur.close()
db.close()

print "Cleaning up GPIO"
GPIO.cleanup()