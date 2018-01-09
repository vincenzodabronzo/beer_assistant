import datetime

mysql = '2013-01-12 15:27:43'
f = '%Y-%m-%d %H:%M:%S'

print datetime.datetime.strptime(mysql, f)

now = datetime.datetime.now()

print now