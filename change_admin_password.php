<?php
// adminユーザーのパスワード変更スクリプト
// このファイルをブラウザで実行して、adminのパスワードを変更してください

include("funcs.php");
$pdo = db_conn();

try {
    // 新しいパスワード
    $new_password = 'matrix123456';
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
    // adminユーザーのパスワードを更新
    $stmt = $pdo->prepare("UPDATE users SET password = :password, updated_at = NOW() WHERE username = 'admin'");
    $stmt->bindValue(':password', $hashed_password, PDO::PARAM_STR);
    $stmt->execute();
    
    $affected = $stmt->rowCount();
    
    if ($affected > 0) {
        echo "✓ adminユーザーのパスワードを変更しました。<br><br>";
        echo "<strong>新しいパスワード: {$new_password}</strong><br><br>";
        echo "<a href='login.php' style='display:inline-block;padding:10px 20px;background:#38bdf8;color:white;text-decoration:none;border-radius:5px;'>ログインページへ</a>";
    } else {
        echo "エラー: adminユーザーが見つかりませんでした。";
    }
    
} catch (PDOException $e) {
    echo "エラー: " . h($e->getMessage());
}
