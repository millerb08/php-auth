<?php 

/**
 * @return \Symfony\Component\HttpFoundation\Request
 */
function request() {
    return \Symfony\Component\HttpFoundation\Request::createFromGlobals();
}
/*
function addBook($title, $description, $ownerId = null)
{
    global $db;
    if (empty($ownerId)) {
        $ownerId = 0;
    }

    try {
        $query = "INSERT INTO books (name, description, owner_id) VALUES (:name, :description, :ownerId)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':name', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':ownerId', $ownerId);
        return $stmt->execute();
    } catch (\Exception $e) {
        throw $e;
    }
}
*/

function addBook($title, $description)
{
    global $db;
    $ownerId = 0;

    try {
        $query = "INSERT INTO books (name, description, owner_id) VALUES (:name, :description, :ownerId)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':name', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':ownerId', $ownerId);
        return $stmt->execute();
    } catch (\Exception $e) {
        throw $e;
    }
}

function updateBook($bookId, $title, $description)
{
    global $db;

    try {
        $query = "UPDATE books SET name=:name, description=:description WHERE id=:bookId";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':name', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':bookId', $bookId);
        return $stmt->execute();
    } catch (\Exception $e) {
        throw $e;
    }
}

function getAllBooks()
{
    global $db;

    try {
        $query = "SELECT books.*, sum(votes.value) as score "
          ."FROM books "
          ."LEFT JOIN votes ON (books.id = votes.book_id) "
          ."GROUP BY books.id "
          ."ORDER BY score DESC";
        /*$query = "SELECT books.*, COALESCE(votes.value,0) as score "
            . " FROM books "
            . " LEFT JOIN votes ON (books.id = votes.book_id) "
            . " GROUP BY books.id "
            . " ORDER BY score DESC";*/
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (\Exception $e) {
        throw $e;
    }
}

function getBook($id)
{
    global $db;

    try {
        $query = "SELECT * FROM books WHERE id = :bookId";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':bookId', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (\Exception $e) {
        throw $e;
    }
}

function vote($bookId, $score)
{
    global $db;
    $userId = 0;

    try {
        $query = 'INSERT INTO votes (book_id, user_id, value) VALUES (:bookId, :userId, :score)';
        $stmt = $db->prepare($query);
        $stmt->bindParam(':bookId', $bookId);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':score', $score);
        $stmt->execute();
    } catch (\Exception $e) {
        throw $e;
    }
}

function findUserByUsername($username)
{
    global $db;

    try {
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch();

    } catch (\Exception $e) {
        throw $e;
    }
}

function createUser($username, $password)
{
    global $db;

    try {
        $query = "INSERT INTO users (username, password, role_id) VALUES (:username, :password, 2)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        return findUserByUsername($username);
    } catch (\Exception $e) {
        throw $e;
    }
}
