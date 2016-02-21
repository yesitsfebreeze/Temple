<?php


# Models
require_once "Core/Models/Storage.php";
require_once "Core/Models/Node.php";
require_once "Core/Models/Plugin.php";

# Plugins
require_once "Core/Plugins/BaseClasses/IdentifierPlugin.php";
require_once "Core/Plugins/BaseClasses/FilterPlugin.php";
require_once "Core/Plugins/BaseClasses/FunctionPlugin.php";

# Services
require_once "Core/Services/Error.php";
require_once "Core/Services/DirectoryHandler.php";
require_once "Core/Services/Config.php";
require_once "Core/Services/Cache.php";
require_once "Core/Services/PluginLoader.php";

require_once "Core/Services/Lexer.php";
require_once "Core/Services/Parser.php";

require_once "Caramel.php";