# ThermoPi

Raspberry Pi Python, PHP and SQL thermostat

## What You'll need. 

- Any sort of Raspberry Pi. I used the Zero. 
- DHT11 or 22 (Requires a small change in the code)
- A relay. I grabbed a random one off ebay. 

The way you wire this up is more or less up to you and can be changed in the main python script as I declare them early on. 
Look up the pinouts if you need to, but you essentially need the 5v, Ground and 2 other GPIO pins to run the whole thing. 

There will be a picture of the current crude setup in this repo (until I design and 3d print a case).  

## Dependancies

### Install Driver for DHT11

  git clone https://github.com/adafruit/Adafruit_Python_DHT.git

  cd Adafruit_Python_DHT

  sudo apt-get install build-essential python-dev 

  sudo python setup.py install

### SQL Server and PHPMyAdmin
    sudo apt-get install mysql-server phpmyadmin 
    nano /etc/apache2/apache2.conf

Add at the end: 

    Include /etc/phpmyadmin/apache.conf
    /etc/init.d/apache2 restart 
(Might Need Sudo, just run sudo !!)

Install python-sql to get them to talk to each other
    
    sudo apt-get install python-dev python-pip libmysqlclient-dev
    pip install MySQL-python
    
This webpage should get you along the right track to getting PHPMyAdmin up and running.
    
    https://pimylifeup.com/raspberry-pi-mysql-phpmyadmin/

The way I have this set up like the image uploaded in this Repo. 

## Config Setup 
I have included an example, but essentially all you need to do here is put the password of your SQL server into it. 

## Web Setup

I decided to use Apache2 as my webserver. You can pretty much use whatever here so long as you can run PHP 

https://www.stewright.me/2015/08/tutorial-install-apache-php-and-mysql-on-a-raspberry-pi-2/

That guide will get you going. You need to make sure you've got apache working which you can do by just visiting the IP address of your pi in a browser. 

From there you can pretty much just drag and drop the files in the HTML folder into the /var/www/html folder of the pi. CyberDuck is a good way to get acess to the files without setting up a samba share or anything like that. 

## You Need to Change all the PHP files to have your password in. 

    use nano /var/www/html/*file*.php 
    
Reboot the Pi and See if it Works.     

##Edit: Restart on Reboot
I was having problems with the power in my area so decided to make sure that this starts on boot
I've attatched the launcher.sh file which will waith 30 seconds after a reboot (to make sure mysql is running) and restart the script. 

you need to edit crontab with this line

    sudo crontab -e
    
at the end of that file add the line

    @reboot sh /home/pi/launcher.sh > / home/pi/logs/conlog 2>&1
    
This should mean that in the case of a power loss when it reboots the script restarts and gets your heating working again. 
