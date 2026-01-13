<?php
include("funcs.php");
admin_check();
$pdo = db_conn();

function render_error($title, $message){
  $t = h($title); $m = h($message);
  echo <<<HTML
<!DOCTYPE html><html lang="ja"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<title>{$t}</title>
<style>
*{box-sizing:border-box} body{margin:0;min-height:100vh;font-family:Inter,sans-serif;background:linear-gradient(135deg,#7dd3fc,#38bdf8,#0ea5e9);display:flex;justify-content:center;align-items:center;padding:24px;color:#0f172a}
.card{max-width:720px;width:100%;background:rgba(255,255,255,.88);backdrop-filter:blur(14px);border-radius:20px;box-shadow:0 20px 50px rgba(0,0,0,.18);padding:26px}
h1{margin:0 0 8px;font-size:20px;font-weight:800} p{margin:0;color:#475569}
a{display:inline-flex;margin-top:16px;padding:10px 14px;border-radius:999px;border:1px solid rgba(15,23,42,.10);text-decoration:none;font-weight:800;background:rgba(255,255,255,.7);color:#0f172a}
</style></head><body>
<div class="card"><h1>{$t}</h1><p>{$m}</p><a href="user_list.php">ユーザー一覧へ戻る</a></div>
</body></html>
HTML;
  exit;
}

$id = $_GET["id"] ?? "";
$is_edit = ($id !== "");
$user = null;

if ($is_edit) {
  $stmt = $pdo->prepare("SELECT * FROM users WHERE id=:id");
  $stmt->bindValue(":id", (int)$id, PDO::PARAM_INT);
  $stmt->execute();
  $user = $stmt->fetch();
  if (!$user) {
    render_error("エラー", "ユーザーが見つかりません。");
  }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id = $_POST["id"] ?? "";
  $username = $_POST["username"] ?? "";
  $name = $_POST["name"] ?? "";
  $admin_flg = isset($_POST["admin_flg"]) ? 1 : 0;
  $password = $_POST["password"] ?? "";

  if ($username === "" || $name === "") {
    render_error("エラー", "ユーザー名と名前は必須です。");
  }

  if ($is_edit && $id === "") {
    render_error("エラー", "IDが指定されていません。");
  }

  // ユーザー名の重複チェック
  $stmt = $pdo->prepare("SELECT id FROM users WHERE username=:username" . ($is_edit ? " AND id != :id" : ""));
  $stmt->bindValue(":username", $username, PDO::PARAM_STR);
  if ($is_edit) {
    $stmt->bindValue(":id", (int)$id, PDO::PARAM_INT);
  }
  $stmt->execute();
  if ($stmt->fetch()) {
    render_error("エラー", "このユーザー名は既に使用されています。");
  }

  if ($is_edit) {
    // 更新処理
    if ($password !== "") {
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("UPDATE users SET username=:username, name=:name, admin_flg=:admin_flg, password=:password, updated_at=NOW() WHERE id=:id");
      $stmt->bindValue(":password", $hashed_password, PDO::PARAM_STR);
    } else {
      $stmt = $pdo->prepare("UPDATE users SET username=:username, name=:name, admin_flg=:admin_flg, updated_at=NOW() WHERE id=:id");
    }
    $stmt->bindValue(":id", (int)$id, PDO::PARAM_INT);
  } else {
    // 新規登録処理
    if ($password === "") {
      render_error("エラー", "新規登録時はパスワードが必須です。");
    }
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password, name, admin_flg, indate) VALUES (:username, :password, :name, :admin_flg, NOW())");
    $stmt->bindValue(":password", $hashed_password, PDO::PARAM_STR);
  }

  $stmt->bindValue(":username", $username, PDO::PARAM_STR);
  $stmt->bindValue(":name", $name, PDO::PARAM_STR);
  $stmt->bindValue(":admin_flg", $admin_flg, PDO::PARAM_INT);

  try {
    $stmt->execute();
    redirect("user_list.php");
  } catch (PDOException $e) {
    render_error("エラー", $e->getMessage());
  }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title><?= $is_edit ? "ユーザー編集" : "ユーザー登録" ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    *{box-sizing:border-box}
    body{
      margin:0; min-height:100vh; font-family:'Inter',sans-serif;
      background: linear-gradient(135deg,#7dd3fc,#38bdf8,#0ea5e9);
      display:flex; justify-content:center; align-items:center;
      padding: 24px; color:#0f172a;
    }
    .wrap{width:100%; max-width:600px}
    .card{
      background: rgba(255,255,255,.88);
      backdrop-filter: blur(14px);
      border-radius: 20px;
      box-shadow: 0 20px 50px rgba(0,0,0,.18);
      padding: 26px 28px 28px;
    }
    .header{display:flex; justify-content:space-between; align-items:flex-start; gap:12px; margin-bottom:18px; flex-wrap:wrap}
    .title{font-size:22px; font-weight:900; margin:0}
    .sub{color:#64748b; font-size:13px; margin-top:6px}
    .btn{
      display:inline-flex; align-items:center; justify-content:center;
      padding: 10px 14px;
      border-radius: 999px;
      border: 1px solid rgba(15,23,42,.10);
      text-decoration:none;
      font-weight:900; font-size:14px;
      background: rgba(255,255,255,.7);
      transition: transform .12s ease, box-shadow .12s ease;
      color:#0f172a;
      white-space:nowrap;
    }
    .btn:hover{transform:translateY(-1px); box-shadow:0 10px 22px rgba(0,0,0,.12)}
    .btn-primary{
      border:none; color:#fff;
      background: linear-gradient(135deg,#38bdf8,#0ea5e9);
      box-shadow: 0 10px 26px rgba(14,165,233,.35);
      cursor:pointer;
    }
    .field{display:flex; flex-direction:column; margin-bottom:18px}
    label{font-size:13px; font-weight:900; color:#334155; margin-bottom:6px}
    input[type="text"], input[type="password"]{
      padding: 12px 14px;
      border-radius: 12px;
      border: 1px solid #e2e8f0;
      font-size: 14px;
      background:#fff;
      transition: all .2s ease;
    }
    input:focus{
      outline:none;
      border-color:#38bdf8;
      box-shadow:0 0 0 3px rgba(56,189,248,.25);
    }
    .checkbox-field{
      display:flex;
      align-items:center;
      gap:8px;
    }
    input[type="checkbox"]{
      width:20px;
      height:20px;
      cursor:pointer;
    }
    .actions{
      display:flex; justify-content:flex-end; gap:10px;
      margin-top: 18px; padding-top: 14px;
      border-top:1px solid rgba(15,23,42,.08);
      flex-wrap:wrap;
    }
    .help-text{
      font-size:12px;
      color:#64748b;
      margin-top:4px;
    }
    @media (max-width: 768px){ body{padding:14px} }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <div class="header">
        <div>
          <h1 class="title"><?= $is_edit ? "ユーザー編集" : "ユーザー登録" ?></h1>
          <div class="sub"><?= $is_edit ? "ID: " . h($user["id"]) : "新しいユーザーを追加" ?></div>
        </div>
        <a class="btn" href="user_list.php">← 一覧へ</a>
      </div>

      <form method="POST" action="user_edit.php">
        <div class="field">
          <label>ユーザー名 *</label>
          <input type="text" name="username" required value="<?= $is_edit ? h($user["username"]) : "" ?>">
        </div>

        <div class="field">
          <label>名前 *</label>
          <input type="text" name="name" required value="<?= $is_edit ? h($user["name"]) : "" ?>">
        </div>

        <div class="field">
          <label>パスワード <?= $is_edit ? "" : "*" ?></label>
          <input type="password" name="password" <?= $is_edit ? "" : "required" ?>>
          <?php if ($is_edit): ?>
            <div class="help-text">変更しない場合は空欄のままにしてください</div>
          <?php endif; ?>
        </div>

        <div class="field">
          <div class="checkbox-field">
            <input type="checkbox" name="admin_flg" id="admin_flg" value="1" <?= ($is_edit && $user["admin_flg"] == 1) ? "checked" : "" ?>>
            <label for="admin_flg" style="margin:0; cursor:pointer;">管理者権限</label>
          </div>
          <div class="help-text">チェックを入れると管理者として登録されます</div>
        </div>

        <?php if ($is_edit): ?>
          <input type="hidden" name="id" value="<?= h($user["id"]) ?>">
        <?php endif; ?>

        <div class="actions">
          <a class="btn" href="user_list.php">キャンセル</a>
          <button class="btn btn-primary" type="submit"><?= $is_edit ? "更新する" : "登録する" ?></button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
