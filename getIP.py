#!/usr/bin/env python3
#-*- coding:utf-8 -*-
#Importieren der nötigen Pakete
import requests
import geocoder
import json
from urllib import urlopen
from tkinter import *
import tkFont

# Ermittlung und Ausgabe des Ortes anhand der IP-Adresse
g = geocoder.ip('me')

standort= g.city

#print("Latitude, Longitude =",g.latlng)
#print("ip Address =",g.ip)
#print("Gelocation Information\n",g.geojson)

# Request-Anfrage von Parametern aus einer URL
userdata = {"ort": standort}

url= 'file://Users/mustafa/Desktop/Wetter/display.php'
r = requests.get(url, params=userdata)

#JSON decodieren
decoded_result = r.json()
#print (decoded_result)
print(json.dumps(decoded_result, indent = 2, sort_keys = True))
datum= decoded_result["datum"]
regen= decoded_result["regenwahrscheinlichkeit"]
temp= decoded_result["temperatur"]
zeit= decoded_result["uhr"]
ort= decoded_result["ort"]

win = Tk()

win.wm_overrideredirect(True)  

win.title("First GUI")
win.geometry('800x480')

T = Text(win, height=5, width=30, font = "Helvetica 76 bold italic")
T.insert(END, ort,"\\n","\n")
T.insert(END, datum,"\\n","\n")
T.insert(END, zeit)
T.insert(END, " Uhr ","\\n","\n")
T.insert(END, regen,"\\n","\n")

T.insert(END, temp)
T.insert(END, " °C ")


T.pack()
mainloop()