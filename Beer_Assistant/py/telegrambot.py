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
        elif command == 'status':
            keyboard = InlineKeyboardMarkup(inline_keyboard=[[InlineKeyboardButton(text='Mashing', callback_data='mashing'), InlineKeyboardButton(text='Fermentation', callback_data='fermentation')], [InlineKeyboardButton(text='Info', callback_data='info')], ])
            bot.sendMessage(chat_id, 'Wanna check beer status instead?', reply_markup=keyboard)
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
    
    if query_data=='mashing':
        bot.sendMessage(chat_id, 'Here\'s mashing status:')
        
    elif query_data=='fermentation':
        sql = ("""SELECT fc.ending_time, fs.temp_max, fs.temp_min, fc.heater, fc.cooler, ft.beer_temp, ft.timestamp FROM fermentation_config AS fc INNER JOIN fermentation_step AS fs ON fc.id = fs.id INNER JOIN fermentation_temp AS ft ON fc.id = ft.id WHERE fc.id=1 ORDER BY ft.timestamp DESC LIMIT 1""")
        cur.execute(sql,)
        rows = cur.fetchall()
    
        for row in rows:
                temp_max = row[1]
                temp_min = row[2]
                force_heat = row[3]
                force_cool = row[4]
                temp = row[5]
                bot.sendMessage(chat_id, '[1 Fermentation opened]\nTemp (Celsius)%s' % temp)

                print "Max temp"
                print temp_max
                print "Min temp"
                print temp_min            
                print "Heat"
                print heat
                print "Cool"
                print cool
        
    elif query_data=='info':
        ts = time.time()
        bot.answerCallbackQuery(query_id, text=datetime.datetime.fromtimestamp(ts).strftime('%H:%M:%S')) #messaggio a comparsa

bot = telepot.Bot(token)
 #MessageLoop(bot, {'chat': on_chat_message}).run_as_thread(); # if chat, execute chat function.
MessageLoop(bot, {'chat': on_chat_message, 'callback_query': on_callback_query}).run_as_thread()
print('Listening ...')
 
while loop:
    time.sleep(10)
    
'''    
bot.message_loop(handle)
print 'Waiting for commands ...'
while 1:
    time.sleep(10)
'''
    

'''
import json 
import requests
import urllib
import time

TOKEN = "458737458:AAHskrQVsMN32bBeexZcruDK3x9hz8vmhaY"
URL = "https://api.telegram.org/bot{}/".format(TOKEN)


def get_url(url):
    response = requests.get(url)
    content = response.content.decode("utf8")
    return content


def get_json_from_url(url):
    content = get_url(url)
    js = json.loads(content)
    return js


def get_updates(offset=None):
    url = URL + "getUpdates?timeout=100"
    if offset:
        url += "&offset={}".format(offset)
    js = get_json_from_url(url)
    return js

def get_last_update_id(updates):
    update_ids = []
    for update in updates["result"]:
        update_ids.append(int(update["update_id"]))
    return max(update_ids)

def echo_all(updates):
    for update in updates["result"]:
        try:
            text = update["message"]["text"]
            chat = update["message"]["chat"]["id"]
            send_message(text, chat)
        except Exception as e:
            print(e)


def get_last_chat_id_and_text(updates):
    num_updates = len(updates["result"])
    last_update = num_updates - 1
    text = updates["result"][last_update]["message"]["text"]
    chat_id = updates["result"][last_update]["message"]["chat"]["id"]
    return (text, chat_id)

def send_message(text, chat_id):
    text = urllib.parse.quote_plus(text)
    url = URL + "sendMessage?text={}&chat_id={}".format(text, chat_id)
    get_url(url)
    
    
def main():
    last_update_id = None
    while True:
        updates = get_updates(last_update_id)
        if len(updates["result"]) > 0:
            last_update_id = get_last_update_id(updates) + 1
            echo_all(updates)
        time.sleep(0.5)

if __name__ == '__main__':
    main()

text, chat = get_last_chat_id_and_text(get_updates())
send_message(text, chat)
'''