#!/bin/sh
/usr/bin/php /home/u276671220/domains/quadque.digital/public_html/crmnotification/ && php artisan schedule:run >> /dev/null 2>&1