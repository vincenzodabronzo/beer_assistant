import sys
import os
import datetime
import time
import MySQLdb
import random
from os.path import dirname

# os.putenv('PYTHONPATH=/home/pi/.local/lib/python2.7/site-packages');
sys.path.append('/home/pi/.local/lib/python2.7/site-packages/telepot')

import telepot
from telepot.loop import MessageLoop
from telepot.namedtuple import InlineKeyboardMarkup, InlineKeyboardButton

# Checking single instance
import singleton
me = singleton.SingleInstance()

hi_a = ['Ciao','Hi','Hi there!','Hello','Hi Sweety', 'Hello Sir']
name_a = ['Sweety','Sweetheart','Princess','Darling','Honey']
id = 1
token = ""
id_a = []
# id_a = [114104929]

# Variables for MySQL
db = MySQLdb.connect(host="localhost", user="pi", passwd="raspberry", db="dbeer")
cur = db.cursor()

sql = ("""SELECT sc.id, sc.telegram, tg.token, tg.user_id FROM system_config AS sc INNER JOIN system_config_telegram_gatekeeper AS tg ON sc.id = tg.id where sc.id=%s ORDER BY sc.id DESC LIMIT 1""", (id, ))
cur.execute(*sql)
rows = cur.fetchall()

for row in rows:
    token = row[2]
    id_a.append( row[3] )

TelegramBot = telepot.Bot(token)

loop = 1

'''
    sql = ("""SELECT ba.id, ba.name, fc.starting_time, fc.ending_time FROM fermentation_config AS fc INNER JOIN batch AS ba ON fc.id = ba.id where fc.ending_time is NULL ORDER BY ba.id DESC LIMIT 1""" )
    cur.execute(sql,)
    rows = cur.fetchall()
    for row in rows:
        print "FERMENTATION - Found 1 active batch with id:"
'''

def on_chat_message(msg):
    chat_id = msg['chat']['id']
    command = msg['text']
    sender = msg['from']['id']

    print 'Received command: %s' % command
        
    if str(sender) in id_a:
        if command == 'hi' or command == 'Hi' or command == 'hello' or command == 'Hello' or command == 'ciao' or command == 'Ciao' or command == 'Hei' or command == 'hei':
            bot.sendMessage(chat_id, random.choice (hi_a))
        elif command == 'joke':
            # os.system("sudo python /home/pi/tg/apricancello.py")
            bot.sendMessage(chat_id, '... I run out of jokes lately ...')
        elif command == 'fermentation' or command == 'Fermentation':
            sql = ("""SELECT fc.ending_time, fs.temp_max, fs.temp_min, ft.heated, ft.cooled, ft.beer_temp, ft.timestamp FROM fermentation_config AS fc INNER JOIN fermentation_step AS fs ON fc.id = fs.id INNER JOIN fermentation_temp AS ft ON fc.id = ft.id WHERE fc.ending_time is NULL ORDER BY ft.timestamp DESC LIMIT 1""")
            cur.execute(sql,)
            rows = cur.fetchall()
            print cur.rowcount
        
            for row in rows:
                    opened = "1"
                    temp_max = row[1]
                    temp_min = row[2]
                    heated = row[3]
                    cooled = row[4]
                    temp = row[5]
            if opened=="0":
                bot.sendMessage(chat_id, "No fermentation opened" )
            else:
                bot.sendMessage(chat_id, '[Fermentation opened]\n\nTemp(Celsius): %s\nTemp max: %s\nTemp min: %s\nHeated: %s\nCooled: %s' % (temp, temp_max, temp_min, heated, cooled) )    
        elif command == 'status' or command == 'Status':
            keyboard = InlineKeyboardMarkup(inline_keyboard=[[InlineKeyboardButton(text='Mashing', callback_data='mashing'), InlineKeyboardButton(text='Fermentation', callback_data='fermentation')], [InlineKeyboardButton(text='Info', callback_data='info')], ])
            bot.sendMessage(chat_id, 'Choose one of these:', reply_markup=keyboard)
        
        else:
            content_type, chat_type, chat_id = telepot.glance(msg)
            bot.sendMessage(chat_id, 'mmm ... It\'s some kind of elvish... I can\'t read it...')
            keyboard = InlineKeyboardMarkup(inline_keyboard=[[InlineKeyboardButton(text='Mashing', callback_data='mashing'), InlineKeyboardButton(text='Fermentation', callback_data='fermentation')], [InlineKeyboardButton(text='Info', callback_data='info')], ])
            bot.sendMessage(chat_id, 'Wanna check beer status instead?', reply_markup=keyboard)
            # Include command list
        
    else:
        bot.sendMessage(chat_id, 'Prove yourself worthy, %s... Please add following ID to authorized users:' % random.choice(name_a) )
        bot.sendMessage(chat_id, sender)

 
def on_callback_query(msg):
    query_id, chat_id, query_data = telepot.glance(msg, flavor='callback_query')
    print('Callback Query:', query_id, chat_id, query_data)
    opened = "0"
    
    if query_data=='mashing':
        sql = ("""SELECT mc.ending_time, ms.target_temp, mt.temperature, mt.heated, mt.pump_recirculated FROM mashing_config AS mc INNER JOIN mashing_step AS ms ON mc.id = ms.id INNER JOIN mashing_temp AS mt ON mc.id = mt.id WHERE mc.ending_time is NULL ORDER BY mt.timestamp DESC LIMIT 1""")
        cur.execute(sql,)
        rows = cur.fetchall()

        for row in rows:
                opened = "1"
                target_temp = row[1]
                heated = row[3]
                pump = row[4]
                temp = row[2]
        if opened=="0":
            bot.sendMessage(chat_id, "No mashing opened" )
        else:
            bot.sendMessage(chat_id, '[Mashing opened]\n\nTemp(Celsius): %s\nTarget temp: %s\nHeated: %s\nPump: %s' % (temp, target_temp, heated, pump) )
        
        bot.answerCallbackQuery( query_id, text="Mashing" )
        
    elif query_data=='fermentation':
        sql = ("""SELECT fc.ending_time, fs.temp_max, fs.temp_min, ft.heated, ft.cooled, ft.beer_temp, ft.timestamp FROM fermentation_config AS fc INNER JOIN fermentation_step AS fs ON fc.id = fs.id INNER JOIN fermentation_temp AS ft ON fc.id = ft.id WHERE fc.ending_time is NULL ORDER BY ft.timestamp DESC LIMIT 1""")
        cur.execute(sql,)
        rows = cur.fetchall()
        print cur.rowcount
        
        for row in rows:
                opened = "1"
                temp_max = row[1]
                temp_min = row[2]
                heated = row[3]
                cooled = row[4]
                temp = row[5]
        if opened=="0":
            bot.sendMessage(chat_id, "No fermentation opened" )
        else:
            bot.sendMessage(chat_id, '[Fermentation opened]\n\nTemp(Celsius): %s\nTemp max: %s\nTemp min: %s\nHeated: %s\nCooled: %s' % (temp, temp_max, temp_min, heated, cooled) )
            
        bot.answerCallbackQuery( query_id, text="Fermentation" )    
    elif query_data=='info':
        ts = time.time()
        bot.answerCallbackQuery(query_id, text=datetime.datetime.fromtimestamp(ts).strftime('%H:%M:%S')) #messaggio a comparsa

bot = telepot.Bot(token)
 #MessageLoop(bot, {'chat': on_chat_message}).run_as_thread(); # if chat, execute chat function.
MessageLoop(bot, {'chat': on_chat_message, 'callback_query': on_callback_query}).run_as_thread()
print('Listening ...')
 
while loop:
    time.sleep(10)