import datetime

mysql = '2013-01-12 15:27:43'
f = '%Y-%m-%d %H:%M:%S'

print datetime.datetime.strptime(mysql, f)

now = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")

buffer = '8'

print now

now_plus_buffer = now + datetime.timedelta(minutes = buffer)

print now