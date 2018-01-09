import datetime
from datetime import timedelta

mysql = '2013-01-12 15:27:43'
f = '%Y-%m-%d %H:%M:%S'

print datetime.datetime.strptime(mysql, f)

now = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")

buffer = '8'

print now

now_plus_buffer = datetime.datetime.now() + datetime.timedelta(minutes = int(buffer))

print now_plus_buffer
print now_plus_buffer.strftime("%Y-%m-%d %H:%M:%S")

if ( datetime.datetime.now() < now_plus_buffer ):
        print "current time minore"
else:
        print "current time maggiore"