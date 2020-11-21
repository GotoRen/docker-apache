# docker-apache
## Overview
- docker-compose を使用して Apache + MySQL + PHP の実行環境プラットフォームを制作
- MAMP, XAMPP, LAMPの環境構築をDockerコンテナで行う
- 以下のコマンドを実行するだけで環境構築を完了できる
  - 面倒なインストール作業やconfigファイルの書き換えを必要としない

## Usage
```
docker-compose up -d
docker ps
CONTAINER ID        IMAGE                 COMMAND                  CREATED             STATUS              PORTS                  NAMES
e42c753c077e        docker-apache_php     "docker-php-entrypoi…"   6 seconds ago       Up 5 seconds        0.0.0.0:8000->80/tcp   docker-apache-php
5d9418c8661e        docker-apache_mysql   "docker-entrypoint.s…"   6 seconds ago       Up 5 seconds        3306/tcp, 33060/tcp    docker-apache-mysql
9c7a84eefc52        phpmyadmin            "/docker-entrypoint.…"   6 seconds ago       Up 5 seconds        0.0.0.0:9000->80/tcp   docker-apache-phpmyadmin


docker images
REPOSITORY                  TAG                 IMAGE ID            CREATED             SIZE
docker-apache_php           latest              f2b04905bae0        41 seconds ago      428MB
docker-apache_mysql         latest              c48ea55266ee        41 seconds ago      373MB
phpmyadmin                  latest              a2f1fd74ea49        2 days ago          469MB
php                         7.2-apache          fcc1426f9f9f        3 days ago          410MB

docker network ls
NETWORK ID          NAME                    DRIVER              SCOPE
22306620d6fe        docker-apache_default   bridge              local

docker exec -it docker-apache-mysql /bin/bash


mysql -u root -p
>> password
create database test_db character set utf8 collate utf8_general_ci;
show databases;
+--------------------+
| Database           |
+--------------------+
| information_schema |
| mysql              |
| performance_schema |
| sys                |
| test_db            |
+--------------------+
5 rows in set (0.01 sec)



quit;
mysql -u root -p test_db < /docker-entrypoint-initdb.d/test.sql

use test_db;
Database changed



mysql> select * from test;
+----+---------------------+
| id | msg                 |
+----+---------------------+
|  1 | add fist message.   |
|  2 | add sconde message. |
|  3 | add third message.  |
+----+---------------------+
3 rows in set (0.00 sec)
```
