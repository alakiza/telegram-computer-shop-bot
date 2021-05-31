<?php

return [
    "token" => "1783279560:AAGQkha59E8VHbiG9Li9Ki5owgbfkdoI8Ls",
    "controllers" => [
        'App\\Http\Controllers\\StartupController',
        'App\\Http\Controllers\\ProductsController',
        'App\\Http\Controllers\\CategoriesController',
        'App\\Http\Controllers\\HelpController',
        'App\\Http\Controllers\\BuyProductController',
    ],
    "goBack" => "Назад",
    "pageUp" => "⬆ Вернуться ⬆",
    "pageDown" => "⬇ Вперёд ⬇",
    "itemsOnPage" => 5,
    "commands" => [
        "start" => [
            "text" => "/start",
            "controller" => 'App\\Http\Controllers\\StartupController',
            "is_stub" => false,
            "commands" => [
                "getCategories" => [
                    "text" => "Категории",
                    "controller" => "App\\Http\Controllers\\CategoriesController",
                    "is_stub" => false,
                    "commands" => [],
                    "next_controller" => [
                        "5" => [
                            "controller" => 'App\\Http\Controllers\\ProductsController',
                            "is_stub" => false,
                            "commands" => [],
                            "next_controller" => [
                                "5" => [
                                    "controller" => 'App\\Http\Controllers\\BuyProductController',
                                    "is_stub" => true,
                                    "commands" => [],
                                ]
                            ]
                        ]
                    ]
                ],
                "getHelp" => [
                    "text" => "Помощь",
                    "controller" => "App\\Http\Controllers\\HelpController",
                    "is_stub" => true,
                    "commands" => []
                ]
            ]
        ]
    ]
];
