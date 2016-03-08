<?php

    function code($group,$case,$grouping = false,$text = false){
        if ($grouping == "start") {
            echo "<div class='group'>";
            echo "<h2 class='group-title'>Group: $group<p>$text</p></h2>";
        }
        $tempalte = file_get_contents("cases/".$group."/".$case.".html");
        echo "<div class='wrapper'>";
        echo "<input type='radio' name='$group' value='$case'>";
        echo "<pre class='case'>";
        echo "<code>";
        echo "$tempalte";
        echo "</code>";
        echo "</pre>";
        echo "</div>";    
        if ($grouping == "end") {
            echo "</div>";
        }
    }
?>
<!doctype HTML>
<html>
    <head>
        <link href='https://fonts.googleapis.com/css?family=Source+Code+Pro:500' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="code.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.js" type="text/javascript"></script>
        <script src="jquery.serializejson.min.js" type="text/javascript"></script>
        <script src="scripts.js" type="text/javascript"></script>
        <meta charset="utf-8">
    </head>
    <body>

        <h1>Caramel syntax case studies.</h1>
        <p>
            This site serves the purpose to return the best possible syntx for caramel.
        </p>

        <form action="/">
            <?php 
                code("1","1","start","Variable assignment.");
                code("1","2");
                code("1","3","end");

                code("2","1","start","For iteration.");
                code("2","2");
                code("2","3","end");

                code("3","1","start","If conditions.");
                code("3","2");
                code("3","3","end");
            ?>

        <div class="wrappy">
            <textarea name="additional" cols="30" rows="10" placeholder="If there are any additional suggestions you could make.&#10;Please let me know here.&#10;&#10;thx"></textarea>
            <br>
            <input class="text" type="text" name="user" placeholder="Your Name">
            <br>
            <input class="submit" type="submit" value="Send your thoughts.">
        </div>
        </form>
    </body>
</html>