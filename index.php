<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php
    require "function.php"
    ?>
</head>
<body>
    <div>
        <p>
            Coin API
        </p>
        <form action="#" method="POST">
            <label>Enter Symbol:</label>
            <input type="text" name='symbol' id='coininput'> </input>
            
            <button type=submit name=submit id='Submit'> Submit</button>
            
            <label>Enter Name:</label>
            <input type="text" id='coininput2' name='name'></input>

            <button type=sumbit name=submit2 id='Submit2'> Submit</button>
        </form>
    </div>

    <div id="infoArea">
    </div>
    

    
</body>
<script src="myfunction.js" type="text/javascript"> </script>
<script type="text/javascript">    
        <?php
        if (isset($_POST['submit'])){
            $result = getinfo($_POST['symbol']);
            // echo('document.getElementById("infoArea").innerHTML=("<div> <p> Name: '. $result->name .' Price: '. $result->quote->USD->price .' </p> </div>");');
        } else if (isset($_POST['submit2'])){
            $result = getinfo($_POST['name']);
            // echo('document.getElementById("infoArea").innerHTML=("<div> <p> Name: '. $result->name .' Price: '. $result->quote->USD->price .' </p> </div>");');

        }else{
            $result = '"test"';
        }
        ?>

        try{
            if(<?=$result?> != "test"){
            decode(<?=$result?>);
        }else{
            console.log("Please enter a value in one of the empty fields.")
        }
        }catch(error){
            console.log(error);
        }
    </script>
</html>
