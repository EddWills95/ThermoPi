# Pi-Python-Thermostat
Raspberry Pi Python, PHP and SQL thermostat

Dependancies
Install Driver for DHT11
git clone https://github.com/adafruit/Adafruit_Python_DHT.git

cd Adafruit_Python_DHT

sudo apt-get install build-essential python-dev 

sudo python setup.py install

SQL Server and PHPMyAdmin
sudo apt-get install mysql-server phpmyadmin 

nano /etc/apache2/apache2.conf

Add at the end: Include /etc/phpmyadmin/apache.conf

/etc/init.d/apache2 restart (Might Need Sudo, just run sudo !!)

Install python-sql to get them to talk to each other
sudo apt-get install python-dev python-pip libmysqlclient-dev

pip install MySQL-python
