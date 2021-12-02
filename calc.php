<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculator</title>
    <?php
    session_start();
    require "functions.php";
    gettopthousand(); 
    ?>
</head>
<body>
    <?php
    if(isset($_SESSION["type"])){
    $coin = query($_SESSION["type"],$_SESSION["userText"]);
    }else{
    echo("Test failed");}?>
    <form action="#" method="POST">
        <table id="resultdisplay">
            <tr>
            <td></td>
            <td></td>
            <th>Weights</th>
            </tr>
            <tr>
                <th>Market Cap</th>
                <td id="MarketCapResult"></td>
                <td><input class=slider type="range" min="0" max="100" name="MrkSld" id="MrkSld" value="50" oninput="MrkOutput.value = this.value"></td>
                <td><input type="num" min="0" max="100" id="MrkOutput" value=50 oninput="MrkSld.value = this.value"></input></td>
            </tr>
            <tr>
                <th>Volume(24hr)</th>
                <td id="VolumeResult"></td>
                <td><input class=slider type="range" min="0" max="100" name="VolSld" id="VolSld" oninput="VolOutput.value = this.value"></td>
                <td><input type="num" min="0" max="100" id="VolOutput" value=50 oninput="VolSld.value = this.value"></td>
            </tr>
            <tr>
                <th>Change 1hr</th>
                <td id="Change1Result"></td>
                <td><input class=slider type="range" min="0" max="100" name="Cng1Sld" id="Cng1Sld" oninput="Cng1Output.value = this.value"></td>
                <td><input type="num" min="0" max="100" id="Cng1Output" value=50 oninput="Cng1Sld.value = this.value"></td>
            </tr>
            <tr>
                <th>Change 24hr</th>
                <td id="Change24Result"></td>
                <td><input class=slider type="range" min="0" max="100" name="Cng24Sld" id="Cng24Sld" oninput="Cng24Output.value = this.value"></td>
                <td><input type="num" min="0" max="100" id="Cng24Output" value=50 oninput="Cng24Sld.value = this.value"></td>
            </tr>
            <tr>
                <th>Change 7d</th>
                <td id="Change7dResult"></td>
                <td><input class=slider type="range" min="0" max="100" name="Cng7Sld" id="Cng7Sld" oninput="Cng7Output.value = this.value"></td>
                <td><input type="num" min="0" max="100" id="Cng7Output" value=50 oninput="Cng7Sld.value = this.value"></td>
            </tr>
        </table>
        
        <button type="sumbit" name="calc">Calculate</button>
        <button><a href="index.php">New Search</a></button>
        <?php if(isset($_POST['MrkSld'])){
            calc($_POST['MrkSld'],$_POST['VolSld'],$_POST['Cng1Sld'],$_POST['Cng24Sld'],$_POST['Cng7Sld'],$coin);
        }; 
            
        ?>
    </form>
    </table>	
    
</body>
<footer>
    <p>DISCLAIMER:Cryptocurrencies are inherently high-risk investments. Big Leaf is not liable for monetary gains or losses that result from decisions influenced by this calculator. Invest at your own risk</p>
</footer>
</html>
