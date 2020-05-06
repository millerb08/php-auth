<?php
require_once __DIR__ .'/../inc/bootstrap.php';

$username = request()->get('email');
$password = request()->get('password');
$confirmPassword = request()->get('confirm_password');

if ($password != $confirmPassword) {
  //$session->getFlashBag()->add('error', 'Passwords do NOT match');
  //redirect('/register.php');
  header('location: /register.php');
}

$user = findUserByUsername($username);
if (!empty($user)) {
  //$session->getFlashBag()->add('error', 'User Already Exists');
  header('location: /register.php');
}

$hashed = password_hash($password, PASSWORD_DEFAULT);
$user = createUser($username, $hashed);
//$session->getFlashBag()->add('success', 'User Added');
//saveUserData($user);
header('location: /');
