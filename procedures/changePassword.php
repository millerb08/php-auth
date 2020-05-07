<?php
require_once __DIR__ . '/../inc/bootstrap.php';
requireAuth();

$currentPassword = request()->get('current_password');
$newPassword = request()->get('password');
$confirmPassword = request()->get('confirm_password');

if($newPassword != $confirmPassword){
  $session->getFlashBag()->add("error", "New password do not match, please try again.");
  redirect("/account.php");
}

$user = findUserByAccesToken();
if(empty($user)){
  $session->getFlashBag()->add("error","Some Error Happened. Try Again. If Continues please log out and back in.");
  redirect("/account.php");
}

if(!password_verify($currentPassword, $user["password"])){
  $session->getFlashBag()->add("error","Current Password is incocrrect, please try again.");
  redirect("/account.php");
}

$updated = updatePassword(password_hash($newPassword, PASSWORD_DEFAULT), $user["id"]);

if(!$updated){
  $session->getFalshBag()->add("error", "Colud not update password, Plese try again");
  redirect("/account.php");
}

$session->getFlashBag()->add("success", "Password updated");
 redirect("/account.php");
