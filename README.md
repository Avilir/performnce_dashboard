# ODF Performance Dashboard

This project is a Perfoprmance test results for the ODF (Openshift Data Foundation).
It generate performance report for differents Version/Build/Platfoem/Test.

The dashboard is build from MariaDB as Backend and PHP server as Frontend, all components of this dashboard
are running as containers with dockercompose.

After cloning this project and building/running it, few changes need to be made.

1. create the files: 
    infta/secrets/db_password.txt        <- This file contain the db working user password
    infra/secrets/db_root_password.txt   <- This file contain the db root password
2. update the DB user password in the file :
    html/secret.php   -> change the value of $pass from ''