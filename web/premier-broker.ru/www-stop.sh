#!/bin/sh

SC_SCRIPT=$1

export SC_SCRIPT

#echo $SC_SCRIPT

/usr/local/bin/sshpass -p '~h%*%Z0W' ssh root@premier-broker.ru -p 50022 /www-stop.sh $SC_SCRIPT