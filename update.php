<?php
include("funcs.php");
login_check();
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
<div class="card"><h1>{$t}</h1><p>{$m}</p><a href="select.php">一覧へ戻る</a></div>
</body></html>
HTML;
  exit;
}

$id           = $_POST["id"] ?? "";
$name         = $_POST["name"] ?? "";
$station      = $_POST["station"] ?? "";
$nation       = $_POST["nation"] ?? "";
$age          = $_POST["years"] ?? "";
$academic     = $_POST["acdemic"] ?? "";
$affiliation  = $_POST["company"] ?? "";
$tech         = $_POST["skill"] ?? "";
$domain       = $_POST["field"] ?? "";
$pr           = $_POST["PR"] ?? "";
$years_exp    = $_POST["years_exp"] ?? "";
$work_style   = $_POST["location"] ?? "";
$desired_rate = $_POST["desired_rate"] ?? "";
$certification= $_POST["certification"] ?? "";
$note         = $_POST["note"] ?? "";
$skill_sheet  = $_POST["excel"] ?? "";

if ($id === "" || $name === "") {
  render_error("更新エラー", "必須項目（ID/氏名）が不足しています。");
}

$stmt = $pdo->prepare(
  "UPDATE engineers SET
     name=:name,
     station=:station,
     nation=:nation,
     age=:age,
     academic=:academic,
     affiliation=:affiliation,
     tech=:tech,
     domain=:domain,
     pr=:pr,
     years_exp=:years_exp,
     work_style=:work_style,
     desired_rate=:desired_rate,
     certification=:certification,
     note=:note,
     skill_sheet=:skill_sheet,
     indate=sysdate()
   WHERE id=:id"
);

$stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
$stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':station', $station, PDO::PARAM_STR);
$stmt->bindValue(':nation', $nation, PDO::PARAM_STR);
$stmt->bindValue(':age', ($age === "" ? null : (int)$age), PDO::PARAM_INT);
$stmt->bindValue(':academic', $academic, PDO::PARAM_STR);
$stmt->bindValue(':affiliation', $affiliation, PDO::PARAM_STR);
$stmt->bindValue(':tech', $tech, PDO::PARAM_STR);
$stmt->bindValue(':domain', $domain, PDO::PARAM_STR);
$stmt->bindValue(':pr', $pr, PDO::PARAM_STR);
$stmt->bindValue(':years_exp', ($years_exp === "" ? null : (int)$years_exp), PDO::PARAM_INT);
$stmt->bindValue(':work_style', $work_style, PDO::PARAM_STR);
$stmt->bindValue(':desired_rate', ($desired_rate === "" ? null : (int)$desired_rate), PDO::PARAM_INT);
$stmt->bindValue(':certification', $certification, PDO::PARAM_STR);
$stmt->bindValue(':note', $note, PDO::PARAM_STR);
$stmt->bindValue(':skill_sheet', $skill_sheet, PDO::PARAM_STR);

try {
  $stmt->execute();
} catch (PDOException $e) {
  render_error("更新エラー", $e->getMessage());
}

redirect("select.php");

