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
        <input type="text" id='coininput' name='userText'></input>
        <button type=submit name=submit id="Submit"> Submit</button>
    </form>
    <?php 
	gettopthousand(); // Sets topthousand entries in the db
    if (isset($_POST['submit'])){
        try{(query("name",$_POST['userText']));
            setcookie("type", "name");
            setcookie("userText", $_POST['userText']);
            header("Location: calc.php");
        }catch(Exception $e){
            try{(query("symbol",$_POST['userText']));
			setcookie("type", "name");
			setcookie("userText", $_POST['userText']);
                header("Location: calc.php");
            }catch(Exception $e){
             		echo($_POST['userText'] . " not found or not within top 1000 coins.");
            }
        }
    }
    topTenPrint();
    ?>
	
</body>
</html>
