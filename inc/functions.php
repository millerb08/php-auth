<?php 
/*
 * Set access to components from \Symfony\Component\HttpFoundation\
 * 1. Session
 * 2. Request
 * 3. Redirect
 */
// 1. session \Symfony\Component\HttpFoundation\Session
/*$session = new \Symfony\Component\HttpFoundation\Session\Session();
$session->start();*/

// 2. request \Symfony\Component\HttpFoundation\Request
function request() {
    return \Symfony\Component\HttpFoundation\Request::createFromGlobals();
}

// 3. redirect \Symfony\Component\HttpFoundation\Response
function redirect($path, $extra = []) {
    $response = \Symfony\Component\HttpFoundation\Response::create(null, \Symfony\Component\HttpFoundation\Response::HTTP_FOUND, ['Location' => $path]);
  if(key_exists('cookies', $extra)){
    foreach($extra["cookies"] as $cookie){
      $response->headers->setCookie($cookie);
    }
  }
    $response->send();
    exit;
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
        return $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (\Exception $e) {
        throw $e;
    }
}

function findUserByAccesToken()
{
    global $db;
    try{
      $userId = decodeJwt("sub");
    }catch(\Exception $e){
    
    }

    try {
        $query = "SELECT * FROM users WHERE id = :userId";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);

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

function updatePassword($password, $userId)
{
    global $db;

    try {
        $query = 'UPDATE users SET password = :password WHERE id = :userId';
        $stmt = $db->prepare($query);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
          return true;
        } else {
          return false;
        }
    } catch (\Exception $e) {
        throw $e;
    }
}
 

function decodeJwt($prop = null){
        Firebase\JWT\JWT::$leeway=1;
        $jwt = Firebase\JWT\JWT::decode(
          request()->cookies->get("access_token"), //cookie o JWT to decode
          getenv("SECRET_KEY"), //SECRET KEY to encode
          ["HS256"] //crypto sistem method to encode and decode
        );
  if($prop === NULL){
        return $jwt;
      }
      /*if($prop == "auth_user_id"){
        $pro = "sub";
      }
      if(!isset($cookie->$prop)){
        return false;
      }*/
      return $jwt->{$prop};
  //return decodeAuthCookie();
}

function isAuthenticated()
{
  if(!request()->cookies->has("access_token")){
    return false;
  }
        try{
        decodeJwt();
        return true;
      }catch(Exception $e){
        return false;
      }
}

function requireAuth()
{
  if (!isAuthenticated()) {
    //global $session;
    //$session->getFlashBag()->add('error', 'Not Authorized');
    $accesToken = new Symfony\Component\HttpFoundation\Cookie("access_token", "Expired", $time()-3600, "/", getenv("COOKIE_DOMAIN"));
    redirect('/login.php', ["cookies" => [$accesToken]]);
  }
}

function display_errors(){
  global $session;
  if(!$session->getFlashBag()->has("error")){
    return;
  }
  $messages = $session->getFlashBag()->get("error");
  $response = '<div class="alert alert-danger alert-dismissable">';
  foreach($messages as $message){
    $response .= "{$message}<br/>";
  }
  
  $response .= "</div>";
  return $response;
}

function display_success() {
    global $session;

    if(!$session->getFlashBag()->has('success')) {
        return;
    }

    $messages = $session->getFlashBag()->get('success');

    $response = '<div class="alert alert-success alert-dismissable">';
    foreach ($messages as $message) {
        $response .= "{$message}<br>";
    }
    $response .= '</div>';

    return $response;
}
