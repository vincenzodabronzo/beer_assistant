import telepot
from telepot.loop import MessageLoop
import sys
import os
import datetime
import time


token = '458737458:AAHskrQVsMN32bBeexZcruDK3x9hz8vmhaY'
TelegramBot = telepot.Bot(token)

id_a = [114104929]

# print TelegramBot.getMe()

def on_chat_message(msg):
    chat_id = msg['chat']['id']
    command = msg['text']
    sender = msg['from']['id']

    print 'Got command: %s' % command
    
    if sender in id_a:
        if command == 'hi':
            bot.sendMessage(chat_id, 'Hei, ciao!')
        elif command == '/joke':
            os.system("sudo python /home/pi/tg/apricancello.py")
            bot.sendMessage(chat_id, 'Ti ho aperto!')
        else:
            bot.sendMessage(chat_id, 'mmm ... It\'s some kind of elvish... I can\' read it')
            bot.sendMessage(chat_id, 'Here\'s a list of approved commands from my dear creator:')
            # Include command list
    else:
        bot.sendMessage(chat_id, 'Prove yourself worthy, Sweetheart...')
        bot.sendMessage(chat_id, 'Please include following ID to authorized users:')
        bot.sendMessage(chat_id, sender)
 
bot = telepot.Bot(token)

MessageLoop(bot, {'chat': on_chat_message}).run_as_thread(); # if chat, esegui il metodo on_chat_message.
print('Listening ...')
 
while 1:
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