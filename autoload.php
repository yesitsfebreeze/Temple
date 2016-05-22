<?php


namespace Caramel;


use Caramel\Services\AutoloaderService;

require_once __DIR__ . "/lib/Services/AutoloadService.php";
require_once __DIR__ . "/Engine.php";

new AutoloaderService(__DIR__ . "/lib");