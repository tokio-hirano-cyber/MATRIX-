<?php
include("funcs.php");
login_check();
$pdo = db_conn();

$stmt = $pdo->prepare("SELECT * FROM engineers ORDER BY id DESC");
$status = $stmt->execute();
if ($status === false) { sql_error($stmt); }
$rows = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>Engineer List</title>
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

    /* ▼ 画面外側の余白を削減 */
    padding: 10px;
  }

  /* ▼ ほぼ全幅で使う（左右のスカスカ解消） */
  .wrap{
    width: 100%;
    max-width: 96vw;   /* 96%の画面幅 */
    margin: 0 auto;
  }

  .card{
    width: 100%;
    max-width: none;   /* 幅制限解除 */

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

    /* ▼ ヘッダーの余白も少し詰める */
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

    padding: 9px 13px; /* 少しコンパクト */
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

  /* ▼ テーブル周りの内側余白を削減 */
  .content{
    padding: 10px 12px;
  }

  .tablewrap{
    overflow: auto;
    border-radius:14px;
    border:1px solid rgba(15,23,42,.10);
    background:#fff;

    /* ▼ 画面の高さにフィット（下の余白を減らす） */
    max-height: calc(100vh - 140px);
  }

  table{
    width:100%;
    border-collapse:collapse;
    min-width: 1100px;
  }

  thead th{
    text-align:left;
    font-size:12px;
    letter-spacing:.04em;
    text-transform: uppercase;
    color:#475569;
    background: #f8fafc;
    border-bottom:1px solid rgba(15,23,42,.08);

    /* ▼ 行の密度UP */
    padding: 10px 10px;

    position: sticky;
    top: 0;
    z-index: 1;
  }

  tbody td{
    /* ▼ 行の密度UP */
    padding: 10px 10px;

    border-bottom:1px solid rgba(15,23,42,.06);
    font-size:14px;
    vertical-align: middle;
    white-space: nowrap;
  }

  tbody tr:hover{background:#f8fafc}

  .pill{
    display:inline-block;
    padding:4px 10px;
    border-radius:999px;
    font-size:12px;
    font-weight:800;
    background: rgba(14,165,233,.10);
    color:#0369a1;
    border: 1px solid rgba(14,165,233,.18);
    max-width: 260px;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .row-actions{display:flex; gap:8px}
  .btn-mini{padding: 8px 12px; font-size: 13px}
  .btn-danger{
    border:none;
    color:#fff;
    background: linear-gradient(135deg,#fb7185,#e11d48);
    box-shadow: 0 10px 22px rgba(225,29,72,.25);
  }

  /* ▼ さらに狭い画面では余白をもっと削る */
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
          <div class="title">NEORISエンジニア一覧</div>
      
        </div>
        <div class="actions">
          <?php if($_SESSION['admin_flg'] == 1): ?>
            <a class="btn" href="user_list.php">ユーザー管理</a>
          <?php endif; ?>
          <a class="btn" href="index.php">＋ 新規登録</a>
          <a class="btn btn-primary" href="index.php">登録画面へ →</a>
          <a class="btn" href="logout.php">ログアウト</a>
        </div>
      </div>

      <div class="content">
        <div class="tablewrap">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>氏名</th>
                <th>最寄り駅</th>
                <th>国籍</th>
                <th>年齢</th>
                <th>所属</th>
                <th>得意技術</th>
                <th>得意分野</th>
                <th>経験年数</th>
                <th>出勤可否</th>
                <th>希望単価</th>
                <th>更新日</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($rows as $r): ?>
                <tr>
                  <td><?= h($r["id"]) ?></td>
                  <td><strong><?= h($r["name"]) ?></strong></td>
                  <td><?= $r["station"] ? h($r["station"]) : '<span class="muted">-</span>' ?></td>
                  <td><?= $r["nation"] ? h($r["nation"]) : '<span class="muted">-</span>' ?></td>
                  <td><?= ($r["age"] !== null && $r["age"] !== "") ? h($r["age"]) : '<span class="muted">-</span>' ?></td>
                  <td><?= $r["affiliation"] ? h($r["affiliation"]) : '<span class="muted">-</span>' ?></td>
                  <td><?= $r["tech"] ? '<span class="pill">'.h($r["tech"]).'</span>' : '<span class="muted">-</span>' ?></td>
                  <td><?= $r["domain"] ? '<span class="pill">'.h($r["domain"]).'</span>' : '<span class="muted">-</span>' ?></td>
                  <td><?= ($r["years_exp"] !== null && $r["years_exp"] !== "") ? h($r["years_exp"]).'年' : '<span class="muted">-</span>' ?></td>
                  <td><?= $r["work_style"] ? h($r["work_style"]) : '<span class="muted">-</span>' ?></td>
                  <td><?= ($r["desired_rate"] !== null && $r["desired_rate"] !== "") ? h($r["desired_rate"]).'万円/月' : '<span class="muted">-</span>' ?></td>
                  <td><?= h($r["indate"]) ?></td>
                  <td>
                    <div class="row-actions">
                      <a class="btn btn-mini" href="detail.php?id=<?= h($r["id"]) ?>">編集</a>
                      <?php if($_SESSION['admin_flg'] == 1): ?>
                        <a class="btn btn-mini btn-danger" href="delete.php?id=<?= h($r["id"]) ?>">削除</a>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
              <?php if(count($rows) === 0): ?>
                <tr><td colspan="13" class="muted" style="padding:16px;">まだデータがありません。右上から登録してください。</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</body>
</html>
