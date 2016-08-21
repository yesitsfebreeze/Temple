{literal}<?php

// you can also create a custom config object and to pass it into an instance
$config = new  \Temple\Engine\Config();
$config->setExtension("myCustomExtension");
$templeInstance = new \Temple\Engine\Instance($config);

// to change a setting afterwards you can just use the setters from our Config object.
$templeInstance = new \Temple\Engine\Instance($config);
$templeInstance->Config()->setExtension("myCustomExtension");

{/literal}