<?php
// ユーザーテーブル作成スクリプト
// このファイルをブラウザで実行して、usersテーブルを作成してください

include("funcs.php");
$pdo = db_conn();

try {
    // テーブル作成SQL
    $sql = "CREATE TABLE IF NOT EXISTS users (
      id INT AUTO_INCREMENT PRIMARY KEY,
      username VARCHAR(128) NOT NULL UNIQUE,
      password VARCHAR(255) NOT NULL,
      name VARCHAR(128) NOT NULL,
      admin_flg INT DEFAULT 0 COMMENT '0:一般ユーザー, 1:管理者',
      indate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
      updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    $pdo->exec($sql);
    echo "✓ usersテーブルを作成しました。<br><br>";
    
    // 既存のユーザーをチェック
    $stmt = $pdo->query("SELECT COUNT(*) as cnt FROM users");
    $count = $stmt->fetch()['cnt'];
    
    if ($count == 0) {
        // 初期ユーザーを登録
        // パスワード: matrix123456
        $admin_hash = password_hash('matrix123456', PASSWORD_DEFAULT);
        // パスワード: user123
        $user_hash = password_hash('user123', PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO users (username, password, name, admin_flg) VALUES (:username, :password, :name, :admin_flg)");
        
        // 管理者ユーザー
        $stmt->execute([
            ':username' => 'admin',
            ':password' => $admin_hash,
            ':name' => '管理者',
            ':admin_flg' => 1
        ]);
        echo "✓ 管理者ユーザー（admin / matrix123456）を作成しました。<br>";
        
        // 一般ユーザー
        $stmt->execute([
            ':username' => 'user',
            ':password' => $user_hash,
            ':name' => '一般ユーザー',
            ':admin_flg' => 0
        ]);
        echo "✓ 一般ユーザー（user / user123）を作成しました。<br><br>";
    } else {
        echo "既にユーザーが登録されています（{$count}件）。<br><br>";
    }
    
    echo "<strong>セットアップが完了しました！</strong><br><br>";
    echo "<a href='login.php' style='display:inline-block;padding:10px 20px;background:#38bdf8;color:white;text-decoration:none;border-radius:5px;'>ログインページへ</a>";
    
} catch (PDOException $e) {
    echo "エラー: " . h($e->getMessage());
}
