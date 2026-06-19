# coachtechフリマ – フリマアプリケーション

**coachtechフリマ** は、ユーザーが商品を出品・購入できるフリマアプリケーションです。  
会員登録・ログイン・商品出品・いいね・コメント・購入など  
フリマサービスの基礎となる機能を実装しています。

---

## 📝 アプリの機能概要

本アプリケーションでは、以下の機能を提供します。

- 会員登録・ログイン・ログアウト（Laravel Fortify）
- メールアドレス認証（MailHogによる確認メール送信）
- 商品一覧表示
- マイリスト表示
- 商品検索
- 商品詳細表示
- いいね機能
- コメント投稿
- 商品購入（Stripeによる決済）
- 配送先変更
- マイページ（出品商品・購入商品一覧）
- プロフィール情報変更
- 商品出品（画像アップロード対応）

---

## 環境構築（Docker）

### 1. リポジトリ取得

```bash
git clone git@github.com:taeko-yanari/coachtech-fleama.git
cd coachtech-fleama
```

### 2. Docker 用 `.env` を作成

Docker の MySQL が起動するために、プロジェクト直下に .env が必要です。

```env
MYSQL_ROOT_PASSWORD=root
MYSQL_DATABASE=laravel_db
MYSQL_USER=laravel_user
MYSQL_PASSWORD=laravel_pass
```

### 3. Laravel 用 `.env` を作成

```bash
cp src/.env.example src/.env
```

`.env` を開き、以下を Docker の設定に合わせて編集：

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```

Stripe 決済機能を使うため、以下も追記してください
（値はStripeダッシュボードのテスト用キーを使用）：

```
STRIPE_KEY=pk_test_xxxxxxxxxxxxxxxx
STRIPE_SECRET=sk_test_xxxxxxxxxxxxxxxx
STRIPE_WEBHOOK_SECRET=whsec_xxxxxxxxxxxxxxxx
```

キーは要件シートの基本設計書タブ(生徒様入力用)に記載しています。

### 4. Docker コンテナ起動

```bash
docker compose up -d --build
```

### 5. Laravel セットアップ

```bash
docker compose exec php bash

# 依存パッケージのインストール前に必要なディレクトリを作成
mkdir -p storage/framework/{cache,sessions,views}

# 依存パッケージのインストール
composer install

# アプリケーションキーの生成
php artisan key:generate

# 書き込み権限を付与
chmod -R 777 storage bootstrap/cache

# DB 初期化（migrate + seeder）
php artisan migrate --seed

# 画像公開用シンボリックリンク
php artisan storage:link
```

---

## Stripe Webhook の設定（ローカル環境）

決済処理は Stripe の Webhook をトリガーとして実装しています。
ローカルで購入フローを確認するには、Stripe CLI を使って Webhook イベントを転送する必要があります。

```bash
# Stripe CLI ログイン（初回のみ）
stripe login

# Webhookイベントをローカルに転送
stripe listen --forward-to localhost/webhook/stripe
```

コマンド実行時に表示される `whsec_...` を `.env` の `STRIPE_WEBHOOK_SECRET` に設定してください。

---

## メールアドレス確認（MailHog）

会員登録後に送信される確認メールは、MailHog で確認できます。

- MailHog：http://localhost:8025

登録後、上記URLにアクセスして確認メール内のリンクからメールアドレス認証を完了してください。

---

## テストの実行方法

PHPUnit によるテスト（会員登録・ログイン・出品・検索・購入・決済など計41件）を用意しています。

```bash
docker compose exec php bash

# 全テスト実行
php artisan test

# 特定のテストファイルのみ実行
php artisan test --filter=ItemSearchTest
```

## 使用技術

- PHP 8.2.29（Docker）
- Laravel 8.83.29
- MySQL 8.0.26（Docker）
- nginx 1.21.1（Docker）
- Docker 29.1.3 / Docker Compose v5.0.1

---

## URL

- アプリ：http://localhost/
- phpMyAdmin：http://localhost:8080/
  - サーバー：mysql
  - ユーザー：laravel_user
  - パスワード：laravel_pass

---

## ER 図

![ER 図](docs/er.png)
