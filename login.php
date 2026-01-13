<?php
include("funcs.php");
already_logged_in();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = $_POST["username"] ?? "";
  $password = $_POST["password"] ?? "";

  if ($username === "" || $password === "") {
    $error = "ユーザー名とパスワードを入力してください。";
  } else {
    $pdo = db_conn();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username=:username");
    $stmt->bindValue(":username", $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password"])) {
      session_start_check();
      $_SESSION['user_id'] = $user["id"];
      $_SESSION['username'] = $user["username"];
      $_SESSION['name'] = $user["name"];
      $_SESSION['admin_flg'] = $user["admin_flg"];
      redirect("select.php");
    } else {
      $error = "ユーザー名またはパスワードが正しくありません。";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ログイン</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; }
    body {
      margin: 0;
      min-height: 100vh;
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #7dd3fc, #38bdf8, #0ea5e9);
      display: flex;
      justify-content: center;
      align-items: center;
      color: #0f172a;
      padding: 24px;
    }
    .card {
      width: 100%;
      max-width: 420px;
      background: rgba(255, 255, 255, 0.88);
      backdrop-filter: blur(14px);
      border-radius: 20px;
      box-shadow: 0 20px 50px rgba(0,0,0,.18);
      padding: 40px;
    }
    .title {
      font-size: 28px;
      font-weight: 700;
      text-align: center;
      margin-bottom: 8px;
    }
    .subtitle {
      text-align: center;
      color: #64748b;
      font-size: 14px;
      margin-bottom: 32px;
    }
    .field {
      margin-bottom: 20px;
    }
    label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      margin-bottom: 6px;
      color: #334155;
    }
    input {
      width: 100%;
      padding: 12px 14px;
      border-radius: 10px;
      border: 1px solid #e2e8f0;
      font-size: 14px;
      transition: all .2s ease;
    }
    input:focus {
      outline: none;
      border-color: #38bdf8;
      box-shadow: 0 0 0 3px rgba(56,189,248,.25);
    }
    .error {
      background: #fee2e2;
      color: #991b1b;
      padding: 12px;
      border-radius: 10px;
      font-size: 13px;
      margin-bottom: 20px;
    }
    button {
      width: 100%;
      border: none;
      padding: 14px;
      border-radius: 999px;
      font-size: 15px;
      font-weight: 700;
      color: white;
      cursor: pointer;
      background: linear-gradient(135deg, #38bdf8, #0ea5e9);
      box-shadow: 0 10px 30px rgba(14,165,233,.45);
      transition: transform .15s ease, box-shadow .15s ease;
    }
    button:hover {
      transform: translateY(-2px);
      box-shadow: 0 16px 36px rgba(14,165,233,.55);
    }
    .info {
      margin-top: 24px;
      padding: 16px;
      background: #f1f5f9;
      border-radius: 10px;
      font-size: 12px;
      color: #475569;
    }
    .info strong {
      display: block;
      margin-bottom: 8px;
      color: #0f172a;
    }
  </style>
</head>
<body>
  <div class="card">
    <h1 class="title">ログイン</h1>
    <p class="subtitle">NEORISエンジニア登録システム</p>

    <?php if ($error): ?>
      <div class="error"><?= h($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
      <div class="field">
        <label>ユーザー名</label>
        <input type="text" name="username" required autofocus>
      </div>

      <div class="field">
        <label>パスワード</label>
        <input type="password" name="password" required>
      </div>

      <button type="submit">ログイン</button>
    </form>

    <div class="info">
      <strong>ログインできない場合</strong>
      QRコード読み込みで簡単ログイン🔜<br>
      ご登録されたメールにてパスワード再設定🔜
    </div>
  </div>
</body>
</html>
