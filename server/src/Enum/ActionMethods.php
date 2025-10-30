<?php

namespace App\Enum;

enum ActionMethods: string {
    case GET = "GET";
    case POST = "POST";
    case PUT = "PUT";
    case DELETE = "DELETE";
}
