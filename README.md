# docker-apache
## Overview
- docker-compose を使用して Apache + MySQL + PHP の実行環境プラットフォームを制作
- MAMP, XAMPP, LAMPの環境構築をDockerコンテナで行う
- 以下のコマンドを実行するだけで環境構築を完了できる
  - 面倒なインストール作業やconfigファイルの書き換えを必要としない
- EC2上で簡単に環境構築ができる
- 挙動がおかしい場合プルリク下さい

## Usage
```
$ docker-compose up -d
================================================
$ docker ps
CONTAINER ID        IMAGE                   COMMAND                  CREATED             STATUS              PORTS                  NAMES
9b758a4a8eef        docker-apache_php       "docker-php-entrypoi…"   7 seconds ago       Up 5 seconds        0.0.0.0:8000->80/tcp   docker-apache-php
f5fca65b97ea        docker-apache_mysql     "docker-entrypoint.s…"   7 seconds ago       Up 5 seconds        3306/tcp, 33060/tcp    docker-apache-mysql
b5626f43b80b        phpmyadmin/phpmyadmin   "/docker-entrypoint.…"   7 seconds ago       Up 5 seconds        0.0.0.0:9000->80/tcp   docker-apache-phpmyadmin

$ docker images
REPOSITORY                  TAG                 IMAGE ID            CREATED             SIZE
docker-apache_mysql         latest              4f97f78ffeee        44 seconds ago      373MB
docker-apache_php           latest              fdb31e8bea88        49 seconds ago      428MB
php                         7.2-apache          fcc1426f9f9f        3 days ago          410MB
phpmyadmin/phpmyadmin       latest              4592b4f19053        5 weeks ago         469MB

$ docker network ls
NETWORK ID          NAME                    DRIVER              SCOPE
e5c3e1986c5f        docker-apache_default   bridge              local
================================================
$ docker exec -it docker-apache-mysql /bin/bash
================================================
# mysql -u root -p
Enter password:  password
================================================
> create database test_db character set utf8 collate utf8_general_ci;
Query OK, 1 row affected (0.00 sec)

> show databases;
+--------------------+
| Database           |
+--------------------+
| information_schema |
| mysql              |
| performance_schema |
| sys                |
| test_db            |
+--------------------+
5 rows in set (0.00 sec)
================================================
> quit;
================================================
# mysql -u root -p test_db < /docker-entrypoint-initdb.d/test.sql
Enter password: password
================================================
# mysql -u root -p
Enter password: password
> use test_db;
Database changed

> show fields from test;
+-------+--------------+------+-----+---------+----------------+
| Field | Type         | Null | Key | Default | Extra          |
+-------+--------------+------+-----+---------+----------------+
| id    | int(11)      | NO   | PRI | NULL    | auto_increment |
| msg   | varchar(255) | NO   |     | NULL    |                |
+-------+--------------+------+-----+---------+----------------+
2 rows in set (0.01 sec)

> select * from test;
+----+---------------------+
| id | msg                 |
+----+---------------------+
|  1 | add fist message.   |
|  2 | add second message. |
|  3 | add third message.  |
+----+---------------------+
3 rows in set (0.00 sec)
================================================
http://localhost:9000/
http://localhost:8000/test/test.php
================================================
$ docker-compose stop
```
- DocumentRoot：htdocs/配下
- SQL設定場所：db/mysql_init
- PHPconfig：conf/php.ini

------
- docker-compose.yml
- mysql/
- php/
- phpmyadmin/