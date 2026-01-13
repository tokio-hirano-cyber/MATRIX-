<?php
// パスワードハッシュ生成スクリプト
// このファイルを実行して、users.sqlのパスワードハッシュを更新してください

$passwords = [
    'admin123' => 'admin',
    'user123' => 'user'
];

echo "パスワードハッシュ生成結果:\n\n";

foreach ($passwords as $password => $username) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    echo "ユーザー名: {$username}\n";
    echo "パスワード: {$password}\n";
    echo "ハッシュ: {$hash}\n";
    echo "SQL: INSERT INTO users (username, password, name, admin_flg) VALUES ('{$username}', '{$hash}', '" . ($username === 'admin' ? '管理者' : '一般ユーザー') . "', " . ($username === 'admin' ? '1' : '0') . ");\n\n";
}

echo "これらのハッシュをusers.sqlファイルにコピーしてください。\n";
