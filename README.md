# 勤怠管理システム（Laravel / PHP）

要件に合わせて、Laravel + Fortify + FormRequest + MailHog を前提にした実装例です。  
本リポジトリは「そのまま実行できる最小骨格」を目的にしており、主要機能（一般/管理者認証、打刻、休憩、申請、承認）のコードを含みます。

## 想定技術

- PHP 8.2+
- Laravel 11
- Laravel Fortify
- MySQL
- MailHog

## 主要ルート

- 一般会員登録: `/register`
- 一般ログイン: `/login`
- 一般打刻画面: `/attendance`
- 一般勤怠一覧: `/attendance/list`
- 一般勤怠詳細: `/attendance/detail/{attendance}`
- 一般申請一覧: `/stamp_correction_request/list`
- 管理ログイン: `/admin/login`
- 管理勤怠一覧: `/admin/attendance/list`
- 管理勤怠詳細: `/admin/attendance/{attendance}`
- スタッフ一覧: `/admin/staff/list`
- スタッフ別月次: `/admin/attendance/staff/{user}`
- 申請承認画面: `/stamp_correction_request/approve/{attendanceCorrectionRequest}`

## セットアップ手順（要 Laravel プロジェクト）

1. `composer require laravel/fortify`
2. `php artisan vendor:publish --provider="Laravel\\Fortify\\FortifyServiceProvider"`
3. 本リポジトリの `app/`, `routes/`, `database/`, `resources/views/` を反映
4. `.env` のメール設定を MailHog に変更
   - `MAIL_MAILER=smtp`
   - `MAIL_HOST=mailhog`
   - `MAIL_PORT=1025`
5. `php artisan migrate`
6. `php artisan serve`

## 補足

- エラーメッセージは `lang/ja/validation.php` の `custom` で日本語要件に合わせています。
- 管理者判定は `users.is_admin` で行います。
- 承認待ち申請は編集不可にし、`承認待ちのため修正はできません。` を表示します。
