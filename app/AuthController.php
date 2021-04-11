<?php

class AuthController {

  public function authenticate() {
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, true);
    
    $username = htmlspecialchars($input['username']);
    $password = htmlspecialchars($input['password']);
    $rememberMe = $input['remember_me'] == 1 ? true : false;

    if ($this->checkUser($username, $password)) {
      $respBody = $this->login($username, $rememberMe);
    } else {
      $respBody = [
        'status' => 'failed',
        'message' => 'Wrong credentials'
      ];
    }
    $response = new JsonResponse();
    $response($respBody);
  }

  public function logout() {
    $db = Db::getDb();
    $user = self::isAuth();
    if ($user) {
      $sql = 'UPDATE api_users SET token = NULL, expiration = NULL WHERE username = ?';
      $stat = $db->pdo->prepare($sql);
      $stat->bindParam(1, $user);
      $stat->execute();
      $respBody = ['status' => 'success', 'message' => 'Logged out'];
    } else {
      $error_response = new ErrorResponse();
      $error_response(401);
    }
    $response = new JsonResponse();
    $response($respBody);
  }

  private function login($username, $rememberMe) {
    $db = Db::getDb();
    $token = $this->generateToken();
    if ($rememberMe) {
      $expDate = time() + (14 * 24 * 60 * 60); //remember for 14 days
    } else {
      $expDate = 0;
    }
    $sql = "UPDATE api_users SET token = '" . $token . "', expiration = {$expDate} WHERE username = ?";
    $stat = $db->pdo->prepare($sql);
    $stat->bindParam(1, $username);
    $stat->execute();

    return [
      'status' => 'success',
      'token' => $token,
      'expires' => $expDate
    ];
  }

  private function generateToken() {
    return str_shuffle(MD5(microtime()));
  }

  private function checkUser($username, $password) {
    $db = Db::getDb();
    $sql = 'SELECT username, password FROM api_users WHERE username = ?';
    $stat = $db->pdo->prepare($sql);
    $stat->bindParam(1, $username);
    $stat->execute();
    $user = $stat->fetch(PDO::FETCH_ASSOC);
    if ($user) {
      if (password_verify($password, $user['password'])) {
        return true;
      }
    }
    return false;
  }

  public static function isAuth() {
    $db = Db::getDb();
    $authHeaderName = 'HTTP_AUTHORIZATION';
    $tokenType = 'Bearer';
    $tokenExpiresHeaderName = 'HTTP_X_EXPIRES';
    if (array_key_exists($authHeaderName, $_SERVER)) {
      $authHeaderValue = $_SERVER[$authHeaderName];
      $reqTokenExpires = (int)$_SERVER[$tokenExpiresHeaderName];
      if (substr($authHeaderValue, 0, strlen($tokenType)) === $tokenType) {
        $reqToken = explode(' ', $authHeaderValue)[1];
        $sql = 'SELECT token, expiration, username FROM api_users WHERE token = ?';
        $stat = $db->pdo->prepare($sql);
        $stat->bindParam(1, $reqToken);
        $stat->execute();
        $authUser = $stat->fetch(PDO::FETCH_ASSOC);
        if ($authUser) {
          if ($reqTokenExpires != 0 and time() < (int)$authUser['expiration']) {
            return $authUser['username'];
          } elseif ($reqTokenExpires == 0) {
            return $authUser['username'];
          } else {
            $sql = 'UPDATE api_users SET token = NULL, expiration = NULL WHERE username = ?';
            $stat = $db->pdo->prepare($sql);
            $stat->bindParam(1, $authUser['username']);
            $stat->execute();
            return false;
          }
        }
      }
    }
    return false;
  }

}
