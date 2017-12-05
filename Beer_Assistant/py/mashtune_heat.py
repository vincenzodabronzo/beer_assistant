'''
Created on 8 Nov 2017

@author: kenzo

DONE:
sudo apt-get install python-pip
sudo rpi-update
sudo reboot
sudo apt-get update
sudo apt-get upgrade
sudo adduser pi gpio

TODOS:

1 - Add multistep support (auto)
2 - 

'''


import MySQLdb
import time
from w1thermsensor import W1ThermSensor 
#from _mysql import NULL
import RPi.GPIO as GPIO

# Checking single instance
import singleton
me = singleton.SingleInstance()



def getTemp():
    #temp_c = random.randint(0,100)
    temperature = sensor.get_temperature()
    return round(temperature, 1)                     

pinHeat = 20                # GPIO pin connected to heat Relay
pinPump = 21                # GPIO pin connected to pump Relay
interval = 2                # sec waiting
heat = 0                    # if set to 1, controller will activate heating
pump_recirculation = 0      # if set to 0, pump will not recirculate water
mashing = 0                 # if set to 0, controller will not perform mashing

# Initializing GPIO
GPIO.setmode(GPIO.BCM)
pinList = [20, 21]
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
    print "Initializing data... "
    
    sql = ("""UPDATE mashing_config SET heat=NULL, pump_recirculation =0 WHERE mashing_config.id=%s""", (id, ))
    
    try:
        # Execute the SQL command
        cur.execute(*sql)
        # Commit your changes in the database
        db.commit()
        print "Initialization...OK"
    except:
        # Rollback in case there is any error
        db.rollback()
        print "*** Initialization ERROR ***"
    
 

 
while(mashing):            

    sql = ("""SELECT mc.ending_time, mc.pump_recirculation, ms.target_temp, mc.heat FROM mashing_config AS mc INNER JOIN mashing_step AS ms ON mc.id = ms.id WHERE mc.id=%s""", (id, ))
    cur.execute(*sql)
    rows = cur.fetchall()
    
    ##### inserire controllo mancanza di riga del mashing step !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! se non ci sono risultati nella select non esce masi: mashing=1 sempre !!!
    
    for row in rows:
        if(row[0] is None):
            temp = getTemp()
            pump_recirculation = row[1]
            target_temp = row[2]
            
            force_heat = row[3]
            
            # Checking current temperature (single step)
            if ( (temp<target_temp and force_heat!=0 ) or force_heat==1 ):
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
            
            sql = ("""INSERT INTO mashing_temp (timestamp, id, temperature, heated, pump_recirculated) VALUES (CURRENT_TIMESTAMP,%s,%s,%s,%s)""",(id, temp, heat, pump_recirculation))
            
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
        
    print "------------------------------------"

cur.close()
db.close()

print "Cleaning up GPIO"
GPIO.cleanup()