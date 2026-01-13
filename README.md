# NEORISエンジニア登録システム

NEORISエンジニアの情報を管理するためのWebアプリケーションです。ログイン・ログアウト機能を備えたセキュアなエンジニア情報管理システムです。

## 機能概要

### 🔐 認証機能
- **ログイン機能** (`login.php`)
  - ユーザー名とパスワードによる認証
  - セッション管理による認証状態の維持
  - パスワードハッシュ化によるセキュアな認証
  - 既にログイン済みの場合は自動リダイレクト

- **ログアウト機能** (`logout.php`)
  - セッション破棄による安全なログアウト
  - ログアウト後はログインページへ自動リダイレクト

### 👥 ユーザー管理機能
- ユーザー一覧表示（管理者のみ）
- ユーザー編集・削除機能
- 管理者権限によるアクセス制御

### 👨‍💻 エンジニア情報管理機能
- **エンジニア登録** (`index.php`, `insert.php`)
  - 氏名、最寄り駅、国籍、年齢などの基本情報
  - 所属、得意技術、得意分野などのスキル情報
  - 経験年数、出勤可否、希望単価などの業務情報
  - 自己PR、メモ、スキルシートなどの詳細情報

- **エンジニア一覧表示** (`select.php`)
  - 登録されたエンジニア情報の一覧表示
  - 検索・フィルタリング機能
  - 編集・削除へのリンク（権限に応じて）

- **エンジニア詳細・編集** (`detail.php`, `update.php`)
  - 登録情報の詳細表示
  - 情報の編集・更新機能

- **エンジニア削除** (`delete.php`)
  - 管理者権限による削除機能

## 技術スタック

- **バックエンド**: PHP
- **データベース**: MySQL
- **フロントエンド**: HTML5, CSS3
- **認証**: セッション管理、パスワードハッシュ化（`password_verify`）
- **セキュリティ**: XSS対策（`htmlspecialchars`）、SQLインジェクション対策（PDOプリペアドステートメント）

## ファイル構成

```
engineer/
├── login.php              # ログイン画面
├── logout.php             # ログアウト処理
├── funcs.php              # 共通関数（DB接続、セッション管理、認証チェック等）
├── index.php              # エンジニア登録画面
├── insert.php             # エンジニア登録処理
├── select.php             # エンジニア一覧画面
├── detail.php             # エンジニア詳細・編集画面
├── update.php             # エンジニア更新処理
├── delete.php             # エンジニア削除処理
├── user_list.php          # ユーザー一覧画面（管理者用）
├── user_edit.php          # ユーザー編集画面
├── user_delete.php        # ユーザー削除処理
├── create_users_table.php # ユーザーテーブル作成スクリプト
├── setup_password.php     # パスワード設定スクリプト
├── change_admin_password.php # 管理者パスワード変更スクリプト
└── css/                   # スタイルシート
    ├── bootstrap.css
    └── ...
```

## セットアップ手順

### 1. データベースの準備

MySQLデータベース `matrix` を作成し、以下のテーブルを作成してください：

```sql
-- エンジニア情報テーブル
CREATE TABLE engineers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255),
  station VARCHAR(255),
  nation VARCHAR(255),
  age INT,
  affiliation VARCHAR(255),
  tech VARCHAR(255),
  domain VARCHAR(255),
  years_exp INT,
  work_style VARCHAR(255),
  desired_rate INT,
  indate DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ユーザーテーブル（create_users_table.phpで作成可能）
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  name VARCHAR(255),
  admin_flg INT DEFAULT 0,
  indate DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

### 2. データベース接続設定

`funcs.php` の `db_conn()` 関数でデータベース接続情報を設定してください：

```php
function db_conn(){
  $dbn  = 'mysql:dbname=matrix;charset=utf8mb4;host=localhost';
  $user = 'root';  // あなたのDBユーザー名
  $pwd  = '';      // あなたのDBパスワード
  // ...
}
```

### 3. 初期ユーザーの作成

`setup_password.php` または `create_users_table.php` を実行して初期ユーザーを作成してください。

### 4. Webサーバーの起動

XAMPPなどのローカル環境でApacheとMySQLを起動し、ブラウザで `http://localhost/matrix/engineer/login.php` にアクセスしてください。

## 使用方法

1. **ログイン**
   - `login.php` にアクセス
   - ユーザー名とパスワードを入力してログイン

2. **エンジニア情報の登録**
   - ログイン後、`index.php` から新規エンジニア情報を登録

3. **エンジニア一覧の確認**
   - `select.php` で登録されたエンジニア情報を一覧表示

4. **エンジニア情報の編集・削除**
   - 一覧画面から各エンジニアの「編集」ボタンをクリック
   - 管理者権限がある場合は「削除」ボタンも表示されます

5. **ログアウト**
   - 画面上部の「ログアウト」ボタンをクリック

## セキュリティ機能

- ✅ XSS対策（`h()` 関数によるエスケープ処理）
- ✅ SQLインジェクション対策（PDOプリペアドステートメント）
- ✅ パスワードハッシュ化（`password_hash` / `password_verify`）
- ✅ セッション管理による認証状態の維持
- ✅ ログインチェック機能（未ログイン時の自動リダイレクト）
- ✅ 管理者権限チェック機能

## 権限管理

- **一般ユーザー**: エンジニア情報の閲覧・登録・編集が可能
- **管理者** (`admin_flg = 1`): 上記に加えて、エンジニア情報の削除とユーザー管理が可能

## 注意事項

- 本システムはローカル環境での使用を想定しています
- 本番環境で使用する場合は、追加のセキュリティ対策（HTTPS化、CSRF対策等）を実装してください
- データベースのバックアップを定期的に取得することを推奨します

## ライセンス

このプロジェクトは内部使用を目的としています。

## 更新履歴

- ログイン・ログアウト機能の実装
- エンジニア情報管理機能の実装
- ユーザー管理機能の実装
- セキュリティ機能の強化
