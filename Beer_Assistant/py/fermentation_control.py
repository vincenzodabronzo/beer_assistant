'''
Created on 8 Nov 2017

@author: kenzo

DONE:


TODOS:

1 - Add multistep support (auto)
2 - 

'''


import MySQLdb
import time
import datetime
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

pinHeat = 20                # GPIO pin connected to heater Relay
pinCool = 21                # GPIO pin connected to cooler Relay
interval = 2                # sec waiting
heat = 0                    # if set to 1, controller will activate heating
cool = 0                    # if set to 1, controller will activate cooling
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
sql = ("""SELECT ba.id, ba.name, fc.starting_time, fc.ending_time FROM fermentation_config AS fc INNER JOIN batch AS ba ON fc.id = ba.id where fc.ending_time is NULL ORDER BY ba.id DESC LIMIT 1""")

cur.execute(sql,)
rows = cur.fetchall()
for row in rows:
    id = row[0]
    print "FERMENTATION - Found 1 active batch with id:"
    print id
    mashing = 1

### Get current time
now = datetime.datetime.now()

while(mashing):            

    sql = ("""SELECT fc.ending_time, fs.temp_max, fs.temp_min, fc.heater, fc.cooler, fs.upper_buffer, fs.upper_last_off, fs.lower_buffer, fs.lower_last_off FROM fermentation_config AS fc INNER JOIN fermentation_step AS fs ON fc.id = fs.id WHERE fc.id=%s""", (id, ))
    cur.execute(*sql)
    rows = cur.fetchall()
    
    ##### inserire controllo mancanza di riga del mashing step !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! se non ci sono risultati nella select non esce masi: mashing=1 sempre !!!
    
    for row in rows:
        if(row[0] is None):
            

            
            temp = getTemp()
            temp_max = row[1]
            temp_min = row[2]
            
            force_heat = row[3]
            force_cool = row[4]
            
            upper_buffer_mins = row[5]
            upper_last_off = row[6]
            lower_buffer_mins = row[7]
            lower_last_off = row[8]
            
            f = '%Y-%m-%d %H:%M:%S'
            
            # Checking current temperature (single step)
            if ( (temp<temp_min and force_heat!=0) or force_heat==1 ):    

                can_turnon_heater = 0
                if(upper_last_off is None):
                    ## update last on with current time
                    can_turnon_heater = 1
                elif ( upper_last_off+datetime.timedelta(minutes=int(upper_buffer_mins)) <= datetime.datetime.now()):
                    can_turnon_heater = 1
                else:
                    can_turnon_heater = 0
                
                if(can_turnon_heater or force_heat==1):               
                    heat = 1
                    GPIO.output(pinHeat, GPIO.LOW)
                
            else:
                heat = 0
                GPIO.output(pinHeat, GPIO.HIGH)
                
                # Check if heat was 1 and if so turn to zero and update last_off
                sql = ("""SELECT heated FROM `fermentation_temp` WHERE id=%s ORDER BY timestamp DESC LIMIT 1""", (id, ))
                cur.execute(*sql)
                result_heat = cur.fetchall()
                for result_heat_1 in result_heat:
                    # Last time was turned on before being switched off
                    if (result_heat_1[0]==1):
                        sql = ("""UPDATE fermentation_step SET upper_last_off = CURRENT_TIMESTAMP WHERE fermentation_step.id=%s""",(id, ))
                        cur.execute(*sql)           
                
            if ( (temp>temp_max and force_cool!=0 ) or force_cool==1 ):
                
                can_turnon_cooler = 0
                if(lower_last_off is None):
                    ## update last on with current time
                    can_turnon_cooler = 1
                elif ( lower_last_off+datetime.timedelta(minutes=int(lower_buffer_mins)) <= datetime.datetime.now()):
                    can_turnon_cooler = 1
                else:
                    can_turnon_cooler = 0
                
                if(can_turnon_cooler or force_cool==1):               
                    cool = 1
                    GPIO.output(pinCool, GPIO.LOW)                

            else:
                cool = 0
                GPIO.output(pinCool, GPIO.HIGH)
                
                  # Check if heat was 1 and if so turn to zero and update last_off
                sql = ("""SELECT cooled FROM `fermentation_temp` WHERE id=%s ORDER BY timestamp DESC LIMIT 1""", (id, ))
                cur.execute(*sql)
                result_cool = cur.fetchall()
                for result_cool_1 in result_cool:
                    # Last time was turned on before being switched off
                    if (result_cool_1[0]==1):
                        sql = ("""UPDATE fermentation_step SET lower_last_off = CURRENT_TIMESTAMP WHERE fermentation_step.id=%s""",(id, ))
                        cur.execute(*sql)                   
        
            
            print "[1 Fermentation opened]"
            print "Temp (Celsius)"
            print temp
            print "Max temp"
            print temp_max
            print "Min temp"
            print temp_min            
            print "Heat"
            print heat
            print "Cool"
            print cool
            
            sql = ("""INSERT INTO fermentation_temp (timestamp, id, temperature, heated, cooled) VALUES (CURRENT_TIMESTAMP,%s,%s,%s,%s)""",(id, temp, heat, cool))
            
            # Se heat e' passato a zero (ma valore precedente era 1), aggiornare last_off
            
            
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