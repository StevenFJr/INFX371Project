<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php
    session_start();
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
            $_SESSION["type"] = "name";
            $_SESSION["userText"] = $_POST['userText'];
            savequery("name",$_POST['userText']);
            header("Location: calc.php");

        }catch(Exception $e){
            try{(query("symbol",$_POST['userText']));
                $_SESSION["type"]="symbol";
                $_SESSION["userText"] = $_POST['userText'];
                savequery("symbol",$_POST['userText']);
                header("Location: calc.php");

            }catch(Exception $e){
                savequery("failedsearch",$_POST['userText']);
                echo($_POST['userText'] . " not found or not within top 1000 coins.");
            }
        }
    }
    topTenPrint();
    ?>
	
</body>
<footer>
    <p>DISCLAIMER:Cryptocurrencies are inherently high-risk investments. Big Leaf is not liable for monetary gains or losses that result from decisions influenced by this calculator. Invest at your own risk</p>
</footer>
</html>
