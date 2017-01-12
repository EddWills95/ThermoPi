#!/usr/bin/python

import RPi.GPIO as GPIO
import Adafruit_DHT
import time
import datetime
import MySQLdb
from apscheduler.schedulers.background import BackgroundScheduler
import logging
import config

logging.basicConfig()

GPIO.setmode(GPIO.BCM)
GPIO.setwarnings(False)

#Define Sensor Type and Pin for DHT11
sensor = 11
pin = 4

#Set Relay Pin
rpin = 17

GPIO.setup(rpin, GPIO.OUT)

GPIO.output(rpin, GPIO.HIGH)  #Set to 'Low'

#Set up arrays
times = []
temps = []

sqlpass = config.sqlPass

#Database Connection
db = MySQLdb.connect(
		host="localhost",
		user="root",
		passwd=sqlpass,
		db="temperature"
		)
db2 = MySQLdb.connect(
		host="localhost",
		user="root",
		passwd=sqlpass,
		db="temperature"
		)
db3 = MySQLdb.connect(
		host="localhost",
		user="root",
		passwd=sqlpass,
		db="temperature"
		)

db4 = MySQLdb.connect(
		host="localhost",
		user="root",
		user="root",
		passwd=sqlpass,
		db="set_temp",
		cursorclass=MySQLdb.cursors.DictCursor
		)

db5 = MySQLdb.connect(
		host="localhost",
		user="root",
		passwd=sqlpass,
		db="temphistory"
		)


db.autocommit(True)
db2.autocommit(True)
db3.autocommit(True)
db4.autocommit(True)
db5.autocommit(True)

#Gets Data from Sensor and Send to SQL
def CurrentSensor():
	cur = db.cursor()  # For "Current Sensor" Update thread"

	print("Getting Current Temperature")
	tempList = []

	for i in range(0,9):
		humidity, temperature = Adafruit_DHT.read_retry(sensor, pin)
		tempList.append(temperature)

	averageCurTemp = sum(tempList) / len(tempList)

	averageCurTemp = int(averageCurTemp)


	print("Current Temperature is: %s" % averageCurTemp)

	cur.execute (("UPDATE temp SET current = %s"), (averageCurTemp))
	db.commit()

	cur.close()

	print("Current Temperature Captured")
	return averageCurTemp

#Compare (needs SQL input when run)
def Compare(averageCurTemp, targetTemp):
	print("Comparing Current and Target")

	# GPIO.setup(16, GPIO.OUT)

	if averageCurTemp < targetTemp:
		print("Lets Get Cooking")
		# GPIO.output(relaypin, GPIO.HIGH) #Relay
		# GPIO.output(16, GPIO.HIGH) #LED
		GPIO.output(rpin, GPIO.LOW)
	else:
		print("Not Worth Putting it On")
		# GPIO.output(relaypin, GPIO.LOW) #Relay
		# GPIO.output(16, GPIO.LOW) #LED
		GPIO.output(rpin, GPIO.HIGH)

	time.sleep(2)

#Gets target data from SQL and update GUI
def UpdateTarget():
	cur2 = db2.cursor()  # For "Update Target/Current"
	cur2.execute("SELECT target FROM temp")
	data = cur2.fetchall()
	for row in data:
		target = row[0]
	cur2.close()
	return target

#Gets current data from SQL and update GUI
def UpdateCurrent():
	cur2 = db2.cursor()  # For "Update Target/Current"
	cur2.execute("SELECT current FROM temp")
	data = cur2.fetchall()
	for row in data:
		current = row[0]
	cur2.close()
	return current

#Compares using SQL data.
def CompareSQL():
	target = UpdateTarget()
	current = UpdateCurrent()
	Compare(current, target)
	# print("Compared // Current: %s Target: %s " %(current, target))


def GetTimesTemps():
	# print("Getting New TimedTemps")
	cur4 = db4.cursor(MySQLdb.cursors.DictCursor)  # For Getting TimedTemps
	cur4.execute("SELECT time, tempset FROM timedtemp")

	temptimes = cur4.fetchall()

	for row in temptimes:
		times.append(row["time"])
		temps.append(row["tempset"])
	cur4.close()

	# print(times)
	# print(temps)
	time.sleep(2)

def GetCurrentTime():
	currenttime = datetime.datetime.now()

	hour = currenttime.hour * 100
	minute = currenttime.minute

	comparetime = hour + minute
	return comparetime


if __name__ == '__main__':

	schedualcurrent = BackgroundScheduler()
	schedualcompare = BackgroundScheduler()
	schedualcurrent.add_job(CurrentSensor, 'interval', seconds=45)
	schedualcompare.add_job(CompareSQL, 'interval', seconds= 20)

	schedualcurrent.start()
	schedualcompare.start()

	while True:
		GetTimesTemps()
		num = 1
		cur3 = db3.cursor()
		cur5 = db5.cursor()

		wholetime = datetime.datetime.now()
		nowmin = wholetime.minute

		for x in range (0,8):
			if (times[x] == GetCurrentTime()):
				cur3.execute("UPDATE temp SET target = %s", temps[x])
				print("Changed to SQL database")
				time.sleep(10)

		time.sleep(0.5)

		cur3.close()
		cur5.close()

		times = []
		temps = []


