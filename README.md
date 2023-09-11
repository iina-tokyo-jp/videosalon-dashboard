# videosalon-dashboard

2023年9月11日より VIDEOSALON dashboardはこちらでgit管理を行います。

# composer管理ライブラリの更新
```
$ composer upgrade
```

# laravelキャッシュクリア
```
$ php artisan cache:clear
$ php artisan config:clear
$ php artisan route:clear
$ php artisan view:clear
```

# キャッシュ作り直し
```
$ composer dump-autoload
$ php artisan clear-compiled
$ php artisan optimize
$ php artisan config:cache
```

# laravelローカル環境で実行
```
php artisan serve
http://127.0.0.1:8000

http://127.0.0.1:8000/login
```
ログインアカウント
|ID|admin@videosalon.org|
|---|---|
|PW|videosalon@a|

# データベースサーバ (PostgreSQL)
起動
```
$ sudo su postgres
$ pg_ctl -D /var/lib/pgsql/data start
```

停止
```
$ sudo su postgres
$ pg_ctl -D /var/lib/pgsql/data stop
```

### macでデータベースサーバ (PostgreSQL)
PostgreSQL v14の場合

必要なら...
```
$ brew reinstall postgresql@14
$ initdb --locale=C -E UTF-8 /opt/homebrew/var/postgres
$ brew services restart postgresql@14
```

BGで起動
```
$ /opt/homebrew/opt/postgresql@14/bin/postgres -D /opt/homebrew/var/postgresql@14 &> postgresql.log &

$ psql -U videosalon
$ psql -U videosalon videosalon_users
```
