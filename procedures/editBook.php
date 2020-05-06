<?php
require_once __DIR__ .'/../inc/bootstrap.php';
//requireAuth();

/*$book = getBook(request()->get('bookId'));
if(!isAdmin() && !isOwner($book["owner_id"])){
  $session->getFlashBag()->add('error', 'Not Authorized');
  redirect("/books.php");
}*/
$bookId = request()->get('bookId');
$bookTitle = request()->get('title');
$bookDescription = request()->get('description');

if (updateBook($bookId, $bookTitle, $bookDescription)) {
    //$session->getFlashBag()->add('success', 'Book Updated');
    //redirect('/books.php');
   header('location: /books.php');
} else {
    //$session->getFlashBag()->add('error', 'Unable to Update Book');
    //redirect('/edit.php?bookId='.$bookId);
    header('location: /edit.php?bookId='.$bookId);
}