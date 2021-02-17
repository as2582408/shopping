## 安裝Apache
sudo apt install apache2<br>

## 安裝mysql
sudo apt install mysql-server<br>

1.設定mysql密碼
sudo mysql_secure_installation<br>
選擇密碼強度 輸入2次新密碼後皆選擇Y<br>

2.進入mysql<br>
sudo mysql<br>

3.建立shopping資料表<br>
create database shopping;<br>

4.修改認證設定<br>
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '[Your Password]';<br>

5.刷新設定<br>
FLUSH PRIVILEGES;<br>

## 安裝PHP
sudo apt-get install php7.2-fpm -y<br>
sudo apt-get install php7.2-common php7.2-json php7.2-gd php7.2-cli php7.2-mbstring php7.2-xml php7.2-opcache php7.2-mysql -y<br>

## 安裝git 
sudo apt-get install git
## 安裝composer
sudo apt-get install composer

## 修改host

sudo nano /etc/hosts<br>

hosts加入<br>
127.0.0.1 shop.user.net<br>
127.0.0.1 shop.admin.net<br>
ctrl + X 離開編輯  輸入 Y 儲存<br>

## 下載專案
1.cd進入shopping資料夾<br>

2.複製環境檔<br>
cp .env.example .env<br>

3.執行composer<br>
composer install<br>

4.建立金鑰<br>
php artisan key:generate<br>

5.修改.env中<br>
DB_DATABASE="shopping"<br>
DB_USERNAME="root"<br>
DB_PASSWORD="你的sql密碼"<br>


6.建立資料表<br>
php artisan migrate<br>
7.進行資料填入<br>
php artisan db:seed<br>
8.進行儲存庫的連接<br>
php artisan storage:link<br>


9.開啟兩個終端機畫面 分別執行<br>
php artisan serve --host=shop.user.net --port=8001<br>
php artisan serve --host=shop.admin.net --port=8002<br>

http://shop.admin.net:8002 <br>
為後台<br>
帳號:admin＠gmail.com 密碼:qaz123<br>

http://shop.user.net:8001<br>
為商店頁面<br>

