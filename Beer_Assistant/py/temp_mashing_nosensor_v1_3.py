'''
Created on 8 Nov 2017

@author: kenzo
'''

import random
import MySQLdb
import time
 
# Variables for MySQL
db = MySQLdb.connect(host="localhost", user="pi", passwd="raspberry", db="dbeer")
cur = db.cursor()
id = 1
 
def getTemp():
    temp_c = random.randint(0,100)
    return round(temp_c,1)
 
for n in range(1, 6):
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

    time.sleep(1)
    
cur.close()
db.close()