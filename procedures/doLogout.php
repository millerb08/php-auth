<?php
require_once __DIR__ . '/../inc/bootstrap.php';

//$session->getFlashBag()->add('success', 'Successfully Logged Out');
$accesToken = new Symfony\Component\HttpFoundation\Cookie("access_token", "Expired", $time()-3600, "/", getenv("COOKIE_DOMAIN"));
redirect('/login.php', ["cookies" => [$accesToken]]);