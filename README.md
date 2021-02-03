# CronjobAdmin

## Description

CronjobAdmin   project  is used for crawling videos from youtube channels and their feeds .And in addition it also available to provide search phrase and the number of iteration for that search phrase crawl .

## Instalation

* Run npm install.

* Run composer install.

* Create database named cronjobadmin in your favorite real MySQL administration tool.

* Copy the .env.example in new .env file and enter your  database and redis credentials in the same file .

* Run you apache, redis and mysql servers (for ubuntu) sudo /etc/init.d/redis-server start   sudo /etc/init.d/apache2 start sudo /etc/init.d/mysql start  

* Run php artisan migrate

* Run php artisan test

* Run php artisan db:seed

* Run php artisan horizon

* Run  cd video-bot && php -S localhost:9999 

* Run php artisan serve

* Open the app on localhost:8000


## Usage

Examples

*  In the channels tab in the header click browser to insert your youtube channels csv file in the csvFiles/testCsv.file or click on the CreateChannel buton and insert manualy.

* In the jobs tab create custom job.


* Run the job in the jobs tab and view your new videos in the videos tab.

