# イメージをビルドするためのベースイメージを設定（FROM イメージ名:タグ名）
FROM php:8.0-fpm

# コンテナ内のサーバー環境変数を設定
ENV TZ Asia/Tokyo

# コンテナビルド時に実行するコマンド（&&で複数のコマンドをつなぎ、\で改行）
# apt-get update：インストール可能なパッケージの「一覧」を更新
# apt-get -y install：パッケージをインストール
# docker-php-ext-install：PHPの拡張ライブラリをインストール
RUN apt-get update && \
	apt-get install -y git unzip libzip-dev libicu-dev libonig-dev && \
	docker-php-ext-install intl pdo_mysql zip bcmath

# php.ini（PHPの設定ファイル）をDockerコンテナ内にコピー（COPY [ローカル側のパス] [コンテナ側のパス]）
COPY ./docker/app/php.ini /usr/local/etc/php/php.ini

# Laravelを使うためComposerをインストール（以下の書き方によりマルチステージビルドという方法でインストール）
COPY --from=composer:2.4 /usr/bin/composer /usr/bin/composer

# コンテナの中でbashを実行した時のカレントディレクトリを指定
# （コンテナを起動している時に $ docker-compose exec コンテナ名 bash というコマンドを実行）
WORKDIR /app
