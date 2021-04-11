<?php

class UsersController {

  public function getUsers() {

    if (AuthController::isAuth()) {
      $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
      $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
      $offset = $limit * ($page - 1);
      
      $db = Db::getDb();
      $sql = "SELECT * FROM students ORDER BY id LIMIT {$limit} OFFSET {$offset}";
      $stat = $db->pdo->prepare($sql);
      $stat->execute();
      $students = $stat->fetchAll(PDO::FETCH_ASSOC);

      $sql = 'SELECT COUNT(*) AS numb FROM students';
      $stat = $db->pdo->prepare($sql);
      $stat->execute();
      $rowCount = (int)$stat->fetch(PDO::FETCH_ASSOC)['numb'];

      if ($students) {
        $respBody = [
          'status' => 'success',
          'students' => $students,
          'page' => $page,
          'records' => count($students),
          'from' => $rowCount
        ];
      } else {
        $respBody = [
          'status' => 'failed',
          'message' => 'Nothing found'
        ];
      }
      $response = new JsonResponse();
      $response($respBody);

    } else {
      $error_response = new ErrorResponse();
      $error_response(401);
    }

  }

}
