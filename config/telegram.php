<?php

return [
    "token" => "1783279560:AAGQkha59E8VHbiG9Li9Ki5owgbfkdoI8Ls",
    "default_time_range" => 300,
    "influx_measurement" => "sensors",
    "influx_fields" => [
        "HR" => [
            "name" => "ЧСС",
            "critical_min" => 50,
            "critical_max" => 80,
        ],
        "SPO2" => [
            "name" => "Оксигенация",
            "critical_min" => 85,
            "critical_max" => null,
        ],
    ],
    "controllers" => [
        'App\\Http\Controllers\\StartupController',
        'App\\Http\Controllers\\GetPatientDataController',
        'App\\Http\Controllers\\GetSensorsDataController',
        'App\\Http\Controllers\\GetDoctorDataController',
    ],
    "goBack" => "Назад",
    "commands" => [
        "start" => [
            "text" => "/start",
            "controller" => 'App\\Http\Controllers\\StartupController',
            "is_stub" => false,
            "commands" => [
                "getPatientData" => [
                    "text" => "Данные о пациентах",
                    "controller" => "App\\Http\Controllers\\GetPatientDataController",
                    "is_stub" => false,
                    "commands" => [
                        "getSensorsData" => [
                            "text" => "Данные датчиков",
                            "controller" => "App\\Http\Controllers\\GetSensorsDataController",
                            "is_stub" => true,
                            "commands" => []
                        ]
                    ]
                ],
                "getDoctorData" => [
                    "text" => "Обо мне",
                    "controller" => "App\\Http\Controllers\\GetDoctorDataController",
                    "is_stub" => true,
                    "commands" => []
                ]
            ]
        ]
    ]
];
