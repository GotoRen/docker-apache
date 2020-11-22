# Docker - for - Apache Web Server
## 💡Overview
- docker-compose を使用して Apache Web Server + MySQL + PHP の実行環境構築ツールを制作
- MAMP, XAMPP, LAMPの環境構築をDockerコンテナ上で行う
- 以下のコマンドを実行するだけで Apache Web Server + MySQL + PHP を完了できる
  - Local：）面倒なインストール作業やconfigファイルの書き換えをしずにローカルにWebサーバを立てられる
  - AWS：）デプロイはEC2にDockerをインストールして`docker-compose up`する
- 挙動がおかしい場合 Pull Request を下さい

## ⚡ Configure
- インストール & バージョン情報
  - Apache/2.4.38 (Debian)
  - MySQL Ver 14.14 Distrib 5.7.27, for Linux (x86_64) using  EditLine wrapper
  - PHP Version 7.2.34
  - phpMyAdmin Version 5.0.4 (最新版)
- ファイル構成
  ```
  docker-apache
       |
       |--- conf
       |     |
       |     |--- php.ini
       |  
       |--- db
       |     |
       |     |--- mysql_init（SQLファイル設置）
       |             |
       |             |--- test.sql（テスト用SQL）
       |
       |--- docker-compose.yml
       |
       |--- htdocs（DocumentRoot）
       |     |
       |     |--- index.php（PHPの情報）
       |     |--- test（プロジェクトファイル）
       |            |
       |            |--- config
       |            |      |
       |            |      |--- db_connect.php（DB接続関数）
       |            |      |--- init.php（DB接続設定ファイル）
       |            |
       |            |--- test.php（テストページ）
       |
       |--- mysl
       |     |
       |     |--- Dockerfile
       |     |--- charaset.cnf
       |
       |--- php
       |     |
       |     |--- Dockerfile     
       |
       |--- phpmyadmin
             |
             |--- sessions
  ```
  - 【主要ディレクトリ】
    - DocumentRoot：htdocs 配下
      - プロジェクトファイルを設置
    - SQL設定場所：db/mysql_init
      - 使用するSQLファイルを設置
    - PHP-Config：conf/php.ini
      - PHPの設定情報を追加する

## 🚀Usage
- Dockerコンテナを立てる
- MySQLにテスト用のDBを構築してテーブルを追加する
- PHPからDBへ接続してデータを取得
- ブラウザで表示する
  ```
  ### コンテナ起動 & 実行
  $ docker-compose up -d
  
  ### 確認
  === * 起動するDockerコンテナ * ===
  $ docker ps
  CONTAINER ID        IMAGE                   COMMAND                  CREATED               STATUS              PORTS                  NAMES
  9b758a4a8eef        docker-apache_php       "docker-php-entrypoi…"   7 seconds ago       Up 5   seconds        0.0.0.0:8000->80/tcp   docker-apache-php
  f5fca65b97ea        docker-apache_mysql     "docker-entrypoint.s…"   7 seconds ago       Up 5   seconds        3306/tcp, 33060/tcp    docker-apache-mysql
  b5626f43b80b        phpmyadmin/phpmyadmin   "/docker-entrypoint.…"   7 seconds ago       Up 5   seconds        0.0.0.0:9000->80/tcp   docker-apache-phpmyadmin
  
  === * 作成されるDockerイメージ * ===
  $ docker images
  REPOSITORY                  TAG                 IMAGE ID            CREATED             SIZE
  docker-apache_mysql         latest              4f97f78ffeee        44 seconds ago      373MB
  docker-apache_php           latest              fdb31e8bea88        49 seconds ago      428MB
  php                         7.2-apache          fcc1426f9f9f        3 days ago          410MB
  phpmyadmin/phpmyadmin       latest              4592b4f19053        5 weeks ago         469MB
  
  === * 作成されるDockerネットワーク * ===
  $ docker network ls
  NETWORK ID          NAME                    DRIVER              SCOPE
  e5c3e1986c5f        docker-apache_default   bridge              local
  
  ### MySQLのコンテナへ入る
  $ docker exec -it docker-apache-mysql /bin/bash
  
  ================================================
  
  ### MySQLへ接続
  # mysql -u root -p
  Enter password: "password" を入力
  
  ### テスト用のDB "test_db" を作成
  > create database test_db character set utf8 collate utf8_general_ci;
  Query OK, 1 row affected (0.00 sec)
  
  ### DB一覧を確認
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
  
  ### 一度MySQLを出る
  > quit;
  
  ### テストテーブルをDBへ追加 『挿入先DB: test_db / テーブル名: test』
  # mysql -u root -p test_db < /docker-entrypoint-initdb.d/test.sql
  Enter password: "password" を入力
  
  ### MySQLへ接続
  # mysql -u root -p
  Enter password: "password" を入力
  
  ### DBをtest_dbに切り替える
  > use test_db;
  Database changed
  
  ### テーブルのフィールドを確認
  > show fields from test;
  +-------+--------------+------+-----+---------+----------------+
  | Field | Type         | Null | Key | Default | Extra          |
  +-------+--------------+------+-----+---------+----------------+
  | id    | int(11)      | NO   | PRI | NULL    | auto_increment |
  | msg   | varchar(255) | NO   |     | NULL    |                |
  +-------+--------------+------+-----+---------+----------------+
  2 rows in set (0.01 sec)
  
  ### データを確認
  > select * from test;
  +----+---------------------+
  | id | msg                 |
  +----+---------------------+
  |  1 | add fist message.   |
  |  2 | add second message. |
  |  3 | add third message.  |
  +----+---------------------+
  3 rows in set (0.00 sec)
  ```
  - indexページ：[http://localhost:8000/](http://localhost:8000/)
  - テストページ：[http://localhost:8000/test/test.php](http://localhost:8000/test/test.php)
    - 追加されたデータを確認<br>
    ![スクリーンショット 2020-11-22 22 33 48](https://user-images.githubusercontent.com/63791288/99905946-7546bd00-2d17-11eb-8e9e-6c068228ecd1.png)
  - phpMyAdmin：[http://localhost:9000/](http://localhost:9000/)
    - 保有DBとテーブルを確認<br>
    ![スクリーンショット 2020-11-22 22 35 49](https://user-images.githubusercontent.com/63791288/99905953-82fc4280-2d17-11eb-9c51-562839d127e2.png)

## 💣Other
- コンテナ & Apache Web Server 停止
  - `$ docker-compose stop`  
- ※ コンテナを削除
  - `$ docker-compose down`   
  - `$ docker rmi [イメージID]`
  - `$ docker network rm [ネットワークID]`

   