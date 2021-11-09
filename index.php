<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php
    require "functions.php";
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
        try{(query("name",$_POST['inText']));
            // echo("Found Name");
        }catch(Exception $e){
            try{(query("symbol",$_POST['inText']));
                // echo("Found Symbol");
            }catch(Exception $e){
                try{(APICall("slug",strtolower($_POST['inText'])));
                }catch(Exception $e){
                    try{(APICall("symbol",$_POST['inText']));
                    }catch(Exception $e){
                        echo("Coin Not Found");
                }
            }
        }
    }
}
    gettopthousand();	
    topTenPrint();
    ?>

    
</body>
</html>
