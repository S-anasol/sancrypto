sancrypto
=========

SanCrypto.info calculator sources

Ставим веб nginx/php само собой

Ставим memcached сервер(порт в скриптах прописан 11711, дефолтный 11211 вроде т.е. надо менять или дефолт или в скриптах)

Заливаем *.sql файл в базу

Вписываем везде логин пароль от базы: web/cron* файлы, web/index.php, nodejs/server.js
 
Дальше идем в папку nodejs и ставим nodejs

Модули нужные для nodejs: memcache, memcached, mysql, node-mysql, node-static

Запускаем server.js под nodejs

Добавляем 2 задания в крон

*/1 * * * * root php /var/www/sancrypto.info/cron.php > /var/www/cron.log &

*/5 * * * * root php /var/www/sancrypto.info/cron2.php > /var/www/cron2.log &
