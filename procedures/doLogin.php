<?php
require_once __DIR__ . '/../inc/bootstrap.php';

$user = findUserByUsername(request()->get("email"));

if(!empty($user)){
  header('location: /login.php');
}

if(!password_verify(request()->get("password"),$user["password"])){
  header('location: /login.php');
}

$expTime = time() + 3600;

$jwt = \Firebase\JWT\JWT::encode([
  "iss" => request()->getBaseUrl(),
  "sub" => "{$user['id']}",
  "exp" => $expTime,
  "ia" => time(),
  "nbf" => time(),
  "is_admin" => $user["role_id"] == 1
], getenv("SECRET_KEY"), "HS256");

$accessToken = new Symfony\Component\HttpFoundation\Cookie("access_roken", $jwt, $expTime, "/", getenv("COOKIE_DOMAIN"));

redirect("/", ["cookies" => [$accessToken]]);
  
  
/*$session->getFlashBag()->add('success', 'Successfully Logged Out');
$cookie = setAuthCookie("expired", 1);
redirect('/login.php', ["cookies" => [$cookie]]);*/