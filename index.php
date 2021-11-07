<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php
    require "new.php";
    ?>
</head>
<body>
    <form action="#" method="POST">
        <label>Enter Symbol/Name:</label>
        <input type="text" id='coininput' name='inText'></input>
        <button type=sumbit name=submit id='Submit'> Submit</button>
    </form>
    <?php 
    if (isset($_POST['submit'])){
        try{(query("slug",$_POST['inText']));
            // echo("Found Slug");
        }catch(Exception $e){
            try{(query("symbol",$_POST['inText']));
                // echo("Found Symbol");
            }catch(Exception $e){
                echo("Coin Not Found");
            }
        }
    }
        // gettopthousand();
        // print_r(query("slug","ethereum"));
    ?>

    
</body>
</html>