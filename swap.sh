#!/bin/bash

# Application directory
homedir=/var/www/mileagemaster

if [ -f $homedir/vuln-index.php ]
then
        echo -e "\n\e[31m[*]\e[0m Swapping to \e[31mVULNERABLE\e[0m Version \e[31m[*]\e[0m"

        echo -e "vuln-index.php \e[31m->\e[0m index.php"
        mv $homedir/index.php $homedir/secure-index.php
        mv $homedir/vuln-index.php $homedir/index.php


        echo -e "vuln-Mileage.php \e[31m->\e[0m Mileage.php"
        mv $homedir/Mileage.php $homedir/secure-Mileage.php
        mv $homedir/vuln-Mileage.php $homedir/Mileage.php

        echo -e "vuln-search.php \e[31m->\e[0m Search.php\n"
        mv $homedir/Search.php $homedir/secure-Search.php
        mv $homedir/vuln-Search.php $homedir/Search.php
else
        echo -e "\n\e[32m[*]\e[0m Swapping to \e[32mSECURED\e[0m Version \e[32m[*]\e[0m"

        echo -e "secure-index.php \e[32m->\e[0m index.php"
        mv $homedir/index.php $homedir/vuln-index.php
        mv $homedir/secure-index.php $homedir/index.php


        echo -e "secure-Mileage.php \e[32m->\e[0m Mileage.php"
        mv $homedir/Mileage.php $homedir/vuln-Mileage.php
        mv $homedir/secure-Mileage.php $homedir/Mileage.php

        echo -e "secure-search \e[32m->\e[0m Search.php\n"
        mv $homedir/Search.php $homedir/vuln-Search.php
        mv $homedir/secure-Search.php $homedir/Search.php
fi