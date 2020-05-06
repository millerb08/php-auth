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

/*$session->getFlashBag()->add('success', 'Successfully Logged Out');
$cookie = setAuthCookie("expired", 1);
redirect('/login.php', ["cookies" => [$cookie]]);*/