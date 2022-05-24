### UPDATE REPOSITORIES

```sh
add-apt-repository ppa:ondrej/apache2 -y
add-apt-repository ppa:ondrej/php -y
apt-get update
apt-get dist-upgrade -Vy
apt-get autoremove --purge -Vy
```

### MYSQL CLIENT

```sh
wget -c https://dev.mysql.com/get/mysql-apt-config_0.8.22-1_all.deb
dpkg -i mysql-apt-config_0.8.22-1_all.deb
rm mysql-apt-config_0.8.22-1_all.deb
```

### INSTALL REQUIRED PACKAGES

```sh
apt-get install -Vy jpegoptim optipng pngquant gifsicle webp ffmpeg software-properties-common supervisor redis-server unzip certbot python3-certbot-apache apache2 libapache2-mod-evasive mysql-client php php-fpm php-common php-mysql php-curl php-mbstring php-xml php-json php-gd php-bcmath php-intl php-zip php-redis php-gmp php-sodium php-swoole
apt-get clean
```

### NVM

```sh
wget -qO- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.1/install.sh | bash
nvm install --lts
nvm cache clear
```

### PHP COMPOSER

```sh
wget https://getcomposer.org/installer -O ~/composer-setup.php -v
php ~/composer-setup.php --install-dir=/usr/local/bin --filename=composer
rm ~/composer-setup.php -f
```

### CLONE THE PROJECT FROM GIT

```sh
cd /var/www/html
git clone -v --depth=1 https://gitlab.com/lloydsgroup/lloyds-backend-laravel.git
```

### ADD APACHE SITE

```sh
cd /etc/apache2/sites-available
```

```apache
ServerAdmin ricardo@lloyds-digital.com
ServerName cms.lloyds.dev
Protocols h2 h2c http/1.1

Include /var/www/html/lloyds-backend-laravel/apache2.conf

ProxyPreserveHost On
ProxyPass / http://localhost:8000/
ProxyPassReverse / http://localhost:8000/
```

### EDIT php.ini

```ini
# nano /etc/php/.../php.ini
upload_max_filesize=1024M
post_max_size=1034M
max_file_uploads=100
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.use_cwd=1
opcache.save_comments=0
opcache.enable_file_override=1
opcache.optimization_level=0xffffffff
max_input_vars=5000 
```

### APACHE PAGESPEED MODULE

```sh
wget -q -O - https://dl.google.com/linux/linux_signing_key.pub | sudo apt-key add -
wget https://dl-ssl.google.com/dl/linux/direct/mod-pagespeed-stable_current_amd64.deb -O ~/mod-pagespeed.deb -v
dpkg -i ~/mod-pagespeed.deb
rm ~/mod-pagespeed.deb -f
```

### ENABLE REQUIRED APACHE MODULES

```sh
a2dismod -f autoindex negotiation status deflate mpm_prefork php8.1
a2enmod proxy_fcgi setenvif mpm_event http2 rewrite ssl headers brotli expires proxy proxy_http proxy_connect proxy_ajp evasive pagespeed
a2enconf php8.1-fpm
service apache2 restart
```

### NPM

```sh
npm install -g svgo
npm install
npm cache clean -f
npm run prod
```

### LARAVEL PRODUCTION

```sh
# In .env file set APP_ENV to production and APP_DEBUG to false
php artisan key:generate
php artisan storage:link
php artisan telescope:install
php artisan telescope:publish
php artisan horizon:install
php artisan horizon:publish
php artisan config:cache
php artisan route:cache
php artisan event:cache
```

### EDIT CRONTAB

```sh
crontab -e
# * * * * * cd /var/www/html/lloyds-backend-laravel && php artisan schedule:run >> /dev/null 2>&1
service cron restart
```

### SUPERVISOR

```sh
cp horizon-worker.conf /etc/supervisor/conf.d
cp octane-worker.conf /etc/supervisor/conf.d

supervisorctl reread
supervisorctl update
supervisorctl restart all
```

### PIPELINES

ERROR:

```sh
Illuminate\Database\QueryException
 SQLSTATE[HY000] [2002] Connection refused (SQL: select * from information_schema.tables where table_schema = lloyds_cms and table_name = settings and table_type = 'BASE TABLE')
```

Solution: Update `.gitlab-ci.yml` file, line 19 and 54.

ERROR:

```sh
Pipeline is stuck in 'PENDING', not running.
```

Solution:

1. Log in to gitlab-runner server, restart and verify runners - `sudo gitlab-runner restart && sudo gitlab-runner verify`
2. Check that your runner is online - <https://gitlab.com/groups/lloydsgroup/-/runners>. After restart, Last contact should be "just now"
