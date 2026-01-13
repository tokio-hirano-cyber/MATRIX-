<?php
include("funcs.php");
admin_check();
$pdo = db_conn();

function render_page($title, $bodyHtml){
  $t = h($title);
  echo <<<HTML
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>{$t}</title>
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
    .card{
      width:100%; max-width:820px;
      background: rgba(255,255,255,.88);
      backdrop-filter: blur(14px);
      border-radius: 20px;
      box-shadow: 0 20px 50px rgba(0,0,0,.18);
      padding: 26px 28px 28px;
    }
    .title{font-size:20px;font-weight:900;margin:0 0 8px}
    .sub{color:#64748b;font-size:13px;margin:0 0 14px}
    .box{
      border:1px solid rgba(15,23,42,.10);
      background:#fff;
      border-radius:14px;
      padding:14px;
      margin-top: 10px;
    }
    .row{display:flex; gap:10px; flex-wrap:wrap; margin-top:16px; justify-content:flex-end}
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
    }
    .btn:hover{transform:translateY(-1px); box-shadow:0 10px 22px rgba(0,0,0,.12)}
    .btn-danger{
      border:none; color:#fff;
      background: linear-gradient(135deg,#fb7185,#e11d48);
      box-shadow: 0 10px 22px rgba(225,29,72,.25);
      cursor:pointer;
    }
    .k{color:#64748b;font-size:12px;text-transform:uppercase;letter-spacing:.06em}
    .v{font-weight:900}
  </style>
</head>
<body>
  <div class="card">
    {$bodyHtml}
  </div>
</body>
</html>
HTML;
  exit;
}

if (($_SERVER["REQUEST_METHOD"] ?? "") === "POST") {
  $id = $_POST["id"] ?? "";
  if ($id === "") {
    render_page("削除エラー", '<h1 class="title">削除エラー</h1><p class="sub">IDが見つかりません。</p><a class="btn" href="user_list.php">ユーザー一覧へ戻る</a>');
  }

  // 自分自身は削除できない
  if ($id == $_SESSION['user_id']) {
    render_page("削除エラー", '<h1 class="title">削除エラー</h1><p class="sub">自分自身を削除することはできません。</p><a class="btn" href="user_list.php">ユーザー一覧へ戻る</a>');
  }

  $stmt = $pdo->prepare("DELETE FROM users WHERE id=:id");
  $stmt->bindValue(":id", (int)$id, PDO::PARAM_INT);

  try {
    $stmt->execute();
    redirect("user_list.php");
  } catch (PDOException $e) {
    render_page("削除エラー", '<h1 class="title">削除エラー</h1><p class="sub">'.h($e->getMessage()).'</p><a class="btn" href="user_list.php">ユーザー一覧へ戻る</a>');
  }
}

$id = $_GET["id"] ?? "";
if ($id === "") {
  render_page("削除エラー", '<h1 class="title">削除エラー</h1><p class="sub">IDが指定されていません。</p><a class="btn" href="user_list.php">ユーザー一覧へ戻る</a>');
}

// 自分自身は削除できない
if ($id == $_SESSION['user_id']) {
  render_page("削除エラー", '<h1 class="title">削除エラー</h1><p class="sub">自分自身を削除することはできません。</p><a class="btn" href="user_list.php">ユーザー一覧へ戻る</a>');
}

$stmt = $pdo->prepare("SELECT id,username,name,admin_flg FROM users WHERE id=:id");
$stmt->bindValue(":id", (int)$id, PDO::PARAM_INT);
$stmt->execute();
$r = $stmt->fetch();
if (!$r) {
  render_page("削除エラー", '<h1 class="title">削除エラー</h1><p class="sub">対象データが見つかりません。</p><a class="btn" href="user_list.php">ユーザー一覧へ戻る</a>');
}

$body = '
  <h1 class="title">このユーザーを削除しますか？</h1>
  <p class="sub">この操作は取り消せません。内容を確認して実行してください。</p>

  <div class="box">
    <div><span class="k">ID</span><div class="v">'.h($r["id"]).'</div></div>
    <div style="margin-top:10px"><span class="k">ユーザー名</span><div class="v">'.h($r["username"]).'</div></div>
    <div style="margin-top:10px"><span class="k">名前</span><div class="v">'.h($r["name"]).'</div></div>
    <div style="margin-top:10px"><span class="k">権限</span><div class="v">'.($r["admin_flg"] == 1 ? "管理者" : "一般ユーザー").'</div></div>
  </div>

  <div class="row">
    <a class="btn" href="user_list.php">キャンセル</a>
    <form method="POST" action="user_delete.php" style="margin:0">
      <input type="hidden" name="id" value="'.h($r["id"]).'">
      <button class="btn btn-danger" type="submit">削除する</button>
    </form>
  </div>
';

render_page("ユーザー削除", $body);
