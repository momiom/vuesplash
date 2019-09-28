# Laravel + Vue.js

## 設定

### PHPのphp-zipモジュールをいれる。
phpのインストール時、どうしてもphp-zipが入らなかった。
php-common-xxxxxxxにすでに入っていると言われるが、実際はない。
下記で入る。問題になっているphp-commonを無視して、remiリポジトリから入れる。
`sudo yum install php-pecl-zip --exclude=php-common --enablerepo=remi-php73`

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
