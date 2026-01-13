<?php
include("funcs.php");
login_check();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>Engineer Registration</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Google Font -->
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
    }

    .wrapper {
      width: 100%;
      max-width: 980px;
      padding: 24px;
    }

    .card {
      background: rgba(255, 255, 255, 0.88);
      backdrop-filter: blur(14px);
      border-radius: 20px;
      box-shadow: 0 20px 50px rgba(0,0,0,.18);
      padding: 32px 40px 40px;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 28px;
    }

    .title {
      font-size: 28px;
      font-weight: 700;
      letter-spacing: .02em;
    }

    .nav-link {
      text-decoration: none;
      color: #0284c7;
      font-weight: 600;
    }

    .grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 22px 28px;
    }

    .field {
      display: flex;
      flex-direction: column;
    }

    .field.full {
      grid-column: 1 / -1;
    }

    label {
      font-size: 13px;
      font-weight: 600;
      margin-bottom: 6px;
      color: #334155;
    }

    input, textarea {
      padding: 12px 14px;
      border-radius: 10px;
      border: 1px solid #e2e8f0;
      font-size: 14px;
      transition: all .2s ease;
    }

    input:focus, textarea:focus {
      outline: none;
      border-color: #38bdf8;
      box-shadow: 0 0 0 3px rgba(56,189,248,.25);
    }

    textarea {
      resize: vertical;
      min-height: 96px;
    }

    .actions {
      margin-top: 28px;
      display: flex;
      justify-content: flex-end;
    }

    button {
      border: none;
      padding: 14px 34px;
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

    @media (max-width: 768px) {
      .grid {
        grid-template-columns: 1fr;
      }
      .card {
        padding: 26px;
      }
    }
  </style>
</head>
<body>

<div class="wrapper">
  <div class="card">

    <div class="header">
      <div class="title">NEORISエンジニア登録</div>
      <div style="display:flex; gap:12px; align-items:center;">
        <span style="font-size:13px; color:#64748b;"><?= h($_SESSION['name']) ?>さん</span>
        <a class="nav-link" href="select.php">一覧を見る →</a>
        <a class="nav-link" href="logout.php">ログアウト</a>
      </div>
    </div>

    <form method="POST" action="insert.php">
      <div class="grid">

        <div class="field">
          <label>氏名 </label>
          <input type="text" name="name" placeholder="NEORIS JACK">
        </div>

        <div class="field">
          <label>最寄り駅</label>
          <input type="text" name="station" placeholder="神宮前">
        </div>

        <div class="field">
          <label>国籍</label>
          <input type="text" name="nation" placeholder="日本">
        </div>

        <div class="field">
          <label>年齢</label>
          <input type="number" name="years">
        </div>
        
        <div class="field">
          <label>学歴</label>
          <input type="text" name="acdemic" placeholder="大卒">
        </div>

        <div class="field">
          <label>所属</label>
          <input type="text" name="company" placeholder="正社員 / フリーランス / 個人">
        </div>
        
        <div class="field">
          <label>得意技術</label>
          <input type="text" name="skill" placeholder="java / python / php">
        </div>

        <div class="field">
          <label>得意分野</label>
          <input type="text" name="field" placeholder="web / back-end / front-end">
        </div>

        <div class="field full">
          <label>自己PR</label>
          <textarea name="PR" placeholder="要件定義から運用・保守まで幅広く経験しており、顧客折衝からメンバー管理を7年ほどやってきました
リーダーに必要な要素としては、プロジェクトに関わる人同士の連携と考えております。
認識違いが起こらないよう、積極的に口頭またはチャットツールでチーム内外のメンバーとコミュニケーションを取り、
業務を円滑に進めてきました。 ..."></textarea>
        </div>

        <div class="field">
          <label>経験年数</label>
          <input type="number" name="years_exp">
        </div>

        <div class="field">
          <label>出勤可否</label>
          <input type="text" name="location" placeholder="常駐 / フルリモート">
        </div>

        <div class="field">
          <label>希望単価（万円 / 月）</label>
          <input type="number" name="desired_rate">
        </div>

        <div class="field">
          <label>所有資格</label>
          <input type="text" name="certification" placeholder="java golden / java sliver">
        </div>


        <div class="field full">
          <label>メモ</label>
          <textarea name="note"></textarea>
        </div>

        <div class="field full">
          <label>スキルシート</label>
          <textarea name="excel"></textarea>
        </div>

      </div>

      <div class="actions">
        <button type="submit">登録する</button>
      </div>
    </form>

  </div>
</div>

</body>
</html>
