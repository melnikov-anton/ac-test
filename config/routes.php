<?php

return [
  '/auth' => [
      'POST' => [
        'controller' => AuthController::class,
        'action' => 'authenticate'
      ],
      'DELETE' => [
        'controller' => AuthController::class,
        'action' => 'logout'
      ]
  ],
  '/users' => [
    'GET' => [
      'controller' => UsersController::class,
      'action' => 'getUsers'
    ]
  ]
];
