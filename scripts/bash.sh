#!/bin/sh
/usr/bin/php /home/u276671220/domains/queleadscrm.com/public_html/crmnotification/ && php artisan schedule:run >> /dev/null 2>&1