<?php

class ErrorResponse {

  public function __invoke($code) {
    switch ($code) {
      case 404:
        header('HTTP/1.1 404 Not Found');
        $message = 'Not Found';
        break;
      case 405:
        header('HTTP/1.1 405 Method Not Allowed');
        $message = 'Method Not Allowed';
        break;
      case 401:
        header('HTTP/1.1 401 Unauthorized');
        $message = 'Unauthorized';
        break;

      default:
        $message = 'Something went wrong';
        break;
    }
    $resp = [
      'code' => $code,
      'message' => $message
    ];
    header('Content-Type: application/json');
    echo json_encode($resp);
    exit();
  }
}
