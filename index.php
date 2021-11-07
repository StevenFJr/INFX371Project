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

    <table id="resultdisplay">
        <tr>
        <td></td>
        <td></td>
        <th>Importance</th>
        </tr>
        <tr>
            <th>Name</th>
            <td id="NameResult"></td>
        </tr>
        <tr>
            <th>Price</th>
            <td id="PriceResult"></td>
            <td><input class=slider type="range" min="0" max="100" name="sld1" value="100"></td>
        </tr>
        <tr>
            <th>Market Cap</th>
            <td id="MarketCapResult"></td>
            <td><input class=slider type="range" min="0" max="100" name="sld2" value="100"></td>
        </tr>
        <tr>
            <th>Change 1hr</th>
            <td id="Change1Result"></td>
            <td><input class=slider type="range" min="0" max="100" name="sld3" value="100"></td>
        </tr>
        <tr>
            <th>Change 24hr</th>
            <td id="Change24Result"></td>
            <td><input class=slider type="range" min="0" max="100" name="sld4" value="100"></td>
        </tr>
        <tr>
            <th>Change 7d</th>
            <td id="Change7dResult"></td>
            <td><input class=slider type="range" min="0" max="100" name="sld5" value="100"></td>
        </tr>

    </table>
    

    
</body>

<script src="myfunction.js" type="text/javascript"> </script>

<script type="text/javascript">    
    <?php
        if (isset($_POST['submit'])){
            $result = getinfosymbol($_POST['symbol']);
            $topten = null;
        } else if (isset($_POST['submit2'])){
            $result = getinfoname($_POST['name']);
            $topten = null;
        }else{
            $result = '"test"';
            $topten = gettopten();
            $topten = json_encode(substr($topten,158,strlen($topten)-157));
        }
    ?>
    try{
        if(<?=$result?> != "test"){
        decode(<?=$result?>);
    }else{
        console.log("Please enter a value in one of the empty fields.");
        decode();
        <?php $que=query(); ?>
        let queryres = "<?=$que[0][0]." ".$que[0][1]." ".$que[1][0]." ".$que[1][1];?>"
        console.log(queryres);
        <?php insertmultiple()?>
    }
    }catch(error){
        console.log(error);
    }
    </script>
</html>