<?php
class JsonResponse {

  public function __invoke($responseBody) {
    header('Content-Type: application/json');
    echo json_encode($responseBody);
    exit();
  }

}
