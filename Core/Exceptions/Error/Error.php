<?php
$root   = realpath($_SERVER["DOCUMENT_ROOT"]);
$assets = explode($root, __DIR__)[1] . "/";

/** @var \Caramel\Exceptions\CaramelException $Exception */
$code         = $Exception->getCode();
$tempFile     = $Exception->getCaramelFile();
$file         = array();
$temp         = explode("/", $tempFile);
$file["name"] = array_pop($temp);
$file["path"] = str_replace($root,"",implode("/", $temp) . "/");

$line   = $Exception->getCaramelLine();
$msg    = $Exception->getMessage();
$traces = $Exception->getTrace();
?>
<html>
    <head>
        <link rel="stylesheet" href="<?php echo $assets; ?>error.css">
        <title>Caramel Error!</title>
    </head>
    <body>
        <main>
            <h1>
                 Caramel Error <?php if ($code != 0) {echo $code;} ?>
            </h1>
            <h2>
                <?php echo $msg; ?>
            </h2>

            <?php if ($file && $file["path"] != "/") { ?>

                <p class="file">
                    <?php
                    echo $file["path"];
                    echo "<span class='highlight'>";
                    echo $file["name"];
                    echo "</span>";

                    if ($line) {
                        echo " in line ";
                        echo "<span class='highlight'>";
                        echo $line;
                        echo "</span>";
                    } ?>
                </p>
            <?php } ?>
            <ul class="traces">
                <?php
                foreach ($traces as $trace) {
                    echo "<li><p>";

                    $tempFile     = $trace["file"];
                    $file         = array();
                    $temp         = explode("/", $tempFile);
                    $file["name"] = array_pop($temp);
                    $file["path"] = str_replace($root,"",implode("/", $temp) . "/");


                    if ($trace["line"]) {
                        echo $file["path"];
                        echo "<span class='highlight'>";
                        echo $file["name"];
                        echo "</span>";
                        echo " in line ";
                        echo "<span class='highlight'>";
                        echo $trace["line"];
                        echo "</span>";
                    }
                    if ($trace["function"]) {
                        echo " in function ";
                        echo "<span class='highlight'>";
                        echo $trace["function"];
                        echo "</span>";
                    }
                    echo "</p></li>";
                }
                ?>
            </ul>
        </main>
    </body>
</html>