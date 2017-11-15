'''
Created on 8 Nov 2017

@author: kenzo
'''

import random
import MySQLdb
import time
from w1thermsensor import W1ThermSensor 
from _mysql import NULL

# import argparse
 
# Variables for MySQL
db = MySQLdb.connect(host="localhost", user="pi", passwd="raspberry", db="dbeer")
cur = db.cursor()

#parser = argparse.ArgumentParser()
#parser.add_argument("id", help="give batch id", type=int)
#args = parser.parse_args()
#id = args.id

id = 2 # id batch - check if ending_time != null
interval = 1 # sec waiting

mashing = 1;

sensor = W1ThermSensor()  
 
def getTemp():
    #temp_c = random.randint(0,100)
    temperature = sensor.get_temperature()
    return round(temperature, 1)
 
while(mashing==1):  
    
    print "Checking ending time..."
    
    sql = ("""SELECT ending_time FROM batch WHERE id=%s""", (id, ))
    cur.execute(*sql)
    rows = cur.fetchall()
    for row in rows:
        print "Analysing results..."
        
        if(row[0]==NULL): 
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
            mashing = 0

cur.close()
db.close()