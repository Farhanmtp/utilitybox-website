# Author : Mubashar
# Date Created: 20/02/2024

#echo -e $PWD

echo -e '\e[1m\e[34mEntering into the Directory...\e[0m\n'

#go to root
#cd ~/

#go to project directory
cd /var/www/html/Utilitybox-Laravel

echo -e '\e[1m\e[34mGit Add ...\e[0m\n'
git add .

echo -e '\e[1m\e[34mGit Commit auto commit...\e[0m\n'
git commit -m " $(date +"%d-%m-%Y-%H-%M-%S") auto system genrated commit "

echo -e '\e[1m\e[34mGoing to take pull...\e[0m\n'
git pull

echo 'Installing composer dependencies'
# rm -rf vendor
composer install --no-interaction --prefer-dist --optimize-autoloader

echo 'Clearing optimizations'
php artisan optimize:clear

php artisan route:clear
echo 'route clear'

sudo chgrp -R www-data storage bootstrap/cache
sudo chmod -R ug+rwx storage bootstrap/cache

echo 'Migrating database'
php artisan migrate --force

echo 'Installing/compiling assets'
npm install --legacy-peer-deps

npm cache clean --force

npm run build

echo -e '\e[1m\e[34mAll Done...\e[0m\n'
