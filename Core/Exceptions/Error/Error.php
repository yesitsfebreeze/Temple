<?php
$assets = str_replace($_SERVER["DOCUMENT_ROOT"] . "/", "", __DIR__) . "/";
/** @var Exception $Exception */
$code         = $Exception->getCode();
$tempFile     = $Exception->getFile();
$file         = array();
$temp         = explode("/", $tempFile);
$file["name"] = array_pop($temp);
$file["path"] = implode("/", $temp) . "/";

$line   = $Exception->getLine();
$msg    = $Exception->getMessage();
$traces = $Exception->getTrace();
?>
<html>
    <head>
        <link rel="stylesheet" href="<?php echo $assets; ?>error.css">
        <script type="text/javascript" src="<?php echo $assets; ?>error.js"></script>
        <title>Caramel Error!</title>
    </head>
    <body>
        <h1>
            Something went wrong with Caramel
        </h1>
        <h2>
            <?php echo $msg; ?>
        </h2>
        <hr>
        <?php if ($file) { ?>
            <p class="file">
                <?php
                echo "in file ";
                echo $file["path"];
                echo "<span class='color'>";
                echo $file["name"];
                echo "</span>";

                if ($line) {
                    echo " in line ";
                    echo "<span class='color'>";
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
                $file["path"] = implode("/", $temp) . "/";

                if ($trace["line"]) {
                    echo " in line ";
                    echo $file["path"];
                    echo "<span class='color'>";
                    echo $file["name"];
                    echo "</span>";
                }
                if ($trace["function"]) {
                    echo " in function ";
                    echo "<span class='color'>";
                    echo $trace["function"];
                    echo "</span>";
                }
                echo "</p></li>";
            }
            ?>
        </ul>

    </body>
</html>