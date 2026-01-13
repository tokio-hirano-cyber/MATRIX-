<?php
include("funcs.php");
admin_check();
$pdo = db_conn();

$stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC");
$status = $stmt->execute();
if ($status === false) { sql_error($stmt); }
$rows = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ユーザー一覧</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
  *{box-sizing:border-box}
  body{
    margin:0;
    min-height:100vh;
    font-family:'Inter',sans-serif;
    background: linear-gradient(135deg,#7dd3fc,#38bdf8,#0ea5e9);
    color:#0f172a;
    padding: 10px;
  }
  .wrap{
    width: 100%;
    max-width: 96vw;
    margin: 0 auto;
  }
  .card{
    width: 100%;
    max-width: none;
    background: rgba(255,255,255,.88);
    backdrop-filter: blur(14px);
    border-radius: 20px;
    box-shadow: 0 20px 50px rgba(0,0,0,.18);
    overflow:hidden;
  }
  .topbar{
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding: 14px 16px;
    border-bottom: 1px solid rgba(15,23,42,.08);
    gap: 10px;
    flex-wrap: wrap;
  }
  .title{
    font-size:20px;
    font-weight:800;
    margin:0;
  }
  .muted{color:#64748b; font-size:13px}
  .actions{
    display:flex;
    gap:10px;
    align-items:center;
    flex-wrap:wrap
  }
  .btn{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding: 9px 13px;
    border-radius: 999px;
    border: 1px solid rgba(15,23,42,.10);
    text-decoration:none;
    font-weight:800;
    font-size:14px;
    background: rgba(255,255,255,.7);
    transition: transform .12s ease, box-shadow .12s ease;
    color:#0f172a;
  }
  .btn:hover{
    transform: translateY(-1px);
    box-shadow: 0 10px 22px rgba(0,0,0,.12)
  }
  .btn-primary{
    border:none;
    color:#fff;
    background: linear-gradient(135deg,#38bdf8,#0ea5e9);
    box-shadow: 0 10px 26px rgba(14,165,233,.35);
  }
  .content{
    padding: 10px 12px;
  }
  .tablewrap{
    overflow: auto;
    border-radius:14px;
    border:1px solid rgba(15,23,42,.10);
    background:#fff;
    max-height: calc(100vh - 140px);
  }
  table{
    width:100%;
    border-collapse:collapse;
    min-width: 800px;
  }
  thead th{
    text-align:left;
    font-size:12px;
    letter-spacing:.04em;
    text-transform: uppercase;
    color:#475569;
    background: #f8fafc;
    border-bottom:1px solid rgba(15,23,42,.08);
    padding: 10px 10px;
    position: sticky;
    top: 0;
    z-index: 1;
  }
  tbody td{
    padding: 10px 10px;
    border-bottom:1px solid rgba(15,23,42,.06);
    font-size:14px;
    vertical-align: middle;
  }
  tbody tr:hover{background:#f8fafc}
  .badge{
    display:inline-block;
    padding:4px 10px;
    border-radius:999px;
    font-size:12px;
    font-weight:800;
    background: rgba(14,165,233,.10);
    color:#0369a1;
    border: 1px solid rgba(14,165,233,.18);
  }
  .badge-admin{
    background: rgba(225,29,72,.10);
    color:#991b1b;
    border-color: rgba(225,29,72,.18);
  }
  .row-actions{display:flex; gap:8px}
  .btn-mini{padding: 8px 12px; font-size: 13px}
  .btn-danger{
    border:none;
    color:#fff;
    background: linear-gradient(135deg,#fb7185,#e11d48);
    box-shadow: 0 10px 22px rgba(225,29,72,.25);
  }
  @media (max-width: 720px){
    body{padding: 8px}
    .topbar{padding: 12px 12px}
    .content{padding: 8px 10px}
    .title{font-size:18px}
  }
</style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <div class="topbar">
        <div>
          <div class="title">ユーザー一覧</div>
          <div class="muted">システムユーザーの管理</div>
        </div>
        <div class="actions">
          <a class="btn" href="select.php">エンジニア一覧</a>
          <a class="btn btn-primary" href="user_edit.php">＋ 新規ユーザー</a>
        </div>
      </div>

      <div class="content">
        <div class="tablewrap">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>ユーザー名</th>
                <th>名前</th>
                <th>権限</th>
                <th>登録日</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($rows as $r): ?>
                <tr>
                  <td><?= h($r["id"]) ?></td>
                  <td><strong><?= h($r["username"]) ?></strong></td>
                  <td><?= h($r["name"]) ?></td>
                  <td>
                    <?php if($r["admin_flg"] == 1): ?>
                      <span class="badge badge-admin">管理者</span>
                    <?php else: ?>
                      <span class="badge">一般ユーザー</span>
                    <?php endif; ?>
                  </td>
                  <td><?= h($r["indate"]) ?></td>
                  <td>
                    <div class="row-actions">
                      <a class="btn btn-mini" href="user_edit.php?id=<?= h($r["id"]) ?>">編集</a>
                      <?php if($r["id"] != $_SESSION['user_id']): ?>
                        <a class="btn btn-mini btn-danger" href="user_delete.php?id=<?= h($r["id"]) ?>">削除</a>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
              <?php if(count($rows) === 0): ?>
                <tr><td colspan="6" class="muted" style="padding:16px;">まだユーザーが登録されていません。</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
