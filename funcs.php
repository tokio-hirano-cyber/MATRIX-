<?php
// XSS対策
function h($str){
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// DB接続（※DB名/ユーザー/パスをあなたの環境に合わせて変更）
function db_conn(){
  $dbn  = 'mysql:dbname=matrix;charset=utf8mb4;host=localhost';
  $user = 'root';
  $pwd  = '';
  try {
    return new PDO($dbn, $user, $pwd, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
  } catch (PDOException $e) {
    exit('DB Connection Error: '.$e->getMessage());
  }
}

function sql_error($stmt){
  $error = $stmt->errorInfo();
  exit('SQL Error: '.$error[2]);
}

function redirect($file){
  header('Location: '.$file);
  exit;
}

// セッション開始
function session_start_check(){
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
}

// ログインチェック
function login_check(){
  session_start_check();
  if (!isset($_SESSION['user_id'])) {
    redirect('login.php');
  }
}

// 管理者チェック
function admin_check(){
  session_start_check();
  if (!isset($_SESSION['user_id']) || $_SESSION['admin_flg'] != 1) {
    redirect('select.php');
  }
}

// ログイン済みチェック（ログインページ用）
function already_logged_in(){
  session_start_check();
  if (isset($_SESSION['user_id'])) {
    redirect('select.php');
  }
}