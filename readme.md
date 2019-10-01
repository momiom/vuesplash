# Laravel + Vue.js

## 設定

### ssh周り
* ポート変更
`/etc/ssh/sshd_config`を編集

*接続できないときは*
* firewallの確認。空いてない場合は許可する。 `firewall-cmd --add-port=20022/tcp --zone=public --permanent`
* .sshディレクトリ周りの権限。 クライアント、サーバー共に.sshディレクトリは`700`、クライアントの秘密鍵とサーバーの公開鍵は`600`
`/var/log/secure`にエラー吐いているので接続できないときにはまず確認。

### PHPのphp-zipモジュールをいれる。
phpのインストール時、どうしてもphp-zipが入らなかった。
php-common-xxxxxxxにすでに入っていると言われるが、実際はない。
下記で入る。問題になっているphp-commonを無視して、remiリポジトリから入れる。
`sudo yum install php-pecl-zip --exclude=php-common --enablerepo=remi-php73`

*追記*
`--exclude=php-common`はおそらく関係ない。リポジトリの指定のとき、PHP -V 7.3.xなのに`remi-php72`とか指定していたからバージョン違いで入らなかっただけっぽい。

### 外部のサーバーで開発する場合
`npm run watch`だけでは動かないので、`php artisan serve --host=0.0.0.0`を同時に実行する。
また、browsersyncも設定しないと3000番で見えない↓

### browsersyncの設定
DOCROOT/webpack.mix.jsのbrowsersyncの設定で、プロキシの対象を上記artisanのサーバーのIPとポートにする。
```javascript
mix.browserSync({
   proxy: {
      target: "k12i.space:8000"
   },
   open: false,
   reloadOnRestart: true
})
```
