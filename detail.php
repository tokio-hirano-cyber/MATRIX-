<?php
include("funcs.php");
login_check();
$pdo = db_conn();

$id = $_GET["id"] ?? "";
if ($id === "") exit("idがありません");

$stmt = $pdo->prepare("SELECT * FROM engineers WHERE id=:id");
$stmt->bindValue(":id", (int)$id, PDO::PARAM_INT);
$status = $stmt->execute();
if ($status === false) { sql_error($stmt); }
$r = $stmt->fetch();
if (!$r) exit("データが見つかりません");
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>Edit Engineer</title>
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
    .wrap{width:100%; max-width:980px}
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
    .grid{display:grid; grid-template-columns:1fr 1fr; gap:16px 18px; margin-top:10px}
    .field{display:flex; flex-direction:column}
    .full{grid-column:1/-1}
    label{font-size:13px; font-weight:900; color:#334155; margin-bottom:6px}
    input,textarea{
      padding: 12px 14px;
      border-radius: 12px;
      border: 1px solid #e2e8f0;
      font-size: 14px;
      background:#fff;
      transition: all .2s ease;
    }
    input:focus,textarea:focus{
      outline:none;
      border-color:#38bdf8;
      box-shadow:0 0 0 3px rgba(56,189,248,.25);
    }
    textarea{resize:vertical; min-height:96px}
    .actions{
      display:flex; justify-content:flex-end; gap:10px;
      margin-top: 18px; padding-top: 14px;
      border-top:1px solid rgba(15,23,42,.08);
      flex-wrap:wrap;
    }
    @media (max-width: 768px){ .grid{grid-template-columns:1fr} body{padding:14px} }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <div class="header">
        <div>
          <h1 class="title">エンジニア編集</h1>
          <div class="sub">ID: <?= h($r["id"]) ?> / 最終更新: <?= h($r["indate"]) ?></div>
        </div>
        <div style="display:flex; gap:10px;">
          <a class="btn" href="select.php">← 一覧へ</a>
          <a class="btn" href="logout.php">ログアウト</a>
        </div>
      </div>

      <form method="POST" action="update.php">
        <div class="grid">
          <div class="field">
            <label>氏名 *</label>
            <input type="text" name="name" required value="<?= h($r["name"]) ?>">
          </div>

          <div class="field">
            <label>最寄り駅</label>
            <input type="text" name="station" value="<?= h($r["station"]) ?>">
          </div>

          <div class="field">
            <label>国籍</label>
            <input type="text" name="nation" value="<?= h($r["nation"]) ?>">
          </div>

          <div class="field">
            <label>年齢</label>
            <input type="number" name="years" value="<?= h($r["age"]) ?>">
          </div>

          <div class="field">
            <label>学歴</label>
            <input type="text" name="acdemic" value="<?= h($r["academic"]) ?>">
          </div>

          <div class="field">
            <label>所属</label>
            <input type="text" name="company" value="<?= h($r["affiliation"]) ?>">
          </div>

          <div class="field">
            <label>得意技術</label>
            <input type="text" name="skill" value="<?= h($r["tech"]) ?>">
          </div>

          <div class="field">
            <label>得意分野</label>
            <input type="text" name="field" value="<?= h($r["domain"]) ?>">
          </div>

          <div class="field full">
            <label>自己PR</label>
            <textarea name="PR"><?= h($r["pr"]) ?></textarea>
          </div>

          <div class="field">
            <label>経験年数</label>
            <input type="number" name="years_exp" value="<?= h($r["years_exp"]) ?>">
          </div>

          <div class="field">
            <label>出勤可否</label>
            <input type="text" name="location" value="<?= h($r["work_style"]) ?>">
          </div>

          <div class="field">
            <label>希望単価（万円 / 月）</label>
            <input type="number" name="desired_rate" value="<?= h($r["desired_rate"]) ?>">
          </div>

          <div class="field">
            <label>所有資格</label>
            <input type="text" name="certification" value="<?= h($r["certification"]) ?>">
          </div>

          <div class="field full">
            <label>メモ</label>
            <textarea name="note"><?= h($r["note"]) ?></textarea>
          </div>

          <div class="field full">
            <label>スキルシート</label>
            <textarea name="excel"><?= h($r["skill_sheet"]) ?></textarea>
          </div>
        </div>

        <input type="hidden" name="id" value="<?= h($r["id"]) ?>">

        <div class="actions">
          <a class="btn" href="select.php">キャンセル</a>
          <button class="btn btn-primary" type="submit">更新する</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
