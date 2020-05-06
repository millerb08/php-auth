<?php
require __DIR__ . '/../inc/bootstrap.php';
//requireAuth();
//$user = getAuthenticatedUser();

vote(request()->get('bookId'), intval(request()->get('vote'))/*, $user["id"]*/);

//redirect('/books.php');
header('location: /books.php');