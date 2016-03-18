<?php

$root   = realpath($_SERVER["DOCUMENT_ROOT"]);
$assets = explode($root, __DIR__)[1] . "/";

/** @var \Caramel\Exceptions\CaramelException $Exception */
$code        = $Exception->getCode();
$caramelFile = $Exception->getCaramelFile();
$caramelLine = $Exception->getCaramelLine();
$file        = $Exception->getFile();
$line        = $Exception->getLine();
$traces      = $Exception->getTrace();

?>
<html>
    <head>
        <link rel="stylesheet" href="<?php echo $assets; ?>error.css">
        <title>Caramel Error!</title>
    </head>
    <body>
        <main>
            <h1>
                Caramel Error <?php echo ($code != 0) ? $code : ""; ?>
            </h1>
            <h2>
                <?php echo $Exception->getMessage(); ?>
                <?php if ($caramelFile) {
                    echo "<p>";
                    $Exception->displayCaramelErrorFile($root, $caramelFile, $caramelLine);
                    echo "</p>";
                } ?>
            </h2>


            <ul class="traces">
                <?php
                echo "<li><p>";
                $Exception->displayCaramelErrorFile($root, $file, $line);
                echo "</p></li>";

                foreach ($traces as $trace) {
                    echo "<li><p>";
                    $Exception->displayCaramelErrorFile($root, $trace["file"], $trace["line"], $trace["function"]);
                    echo "</p></li>";
                }
                ?>
            </ul>
        </main>
    </body>
</html>