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
	<?php
	$display = false;
	?>
    <form action="#" method="POST">
        <label>Enter Symbol/Name:</label>
        <input type="text" id='coininput' name='userText'></input>
        <button type=sumbit name=submit id="Submit"> Submit</button>
    </form>
    <?php 
	gettopthousand();
    if (isset($_POST['submit'])){
        try{(query("name",$_POST['userText']));
            $display = true;
        }catch(Exception $e){
            try{(query("symbol",$_POST['userText']));
                $display = true;
            }catch(Exception $e){
                $display = false;
                echo($_POST['userText'] . " not found or not within top 1000 coins.");
            }
        }
    }	
    ?>
	<?php if($display) : ?>
		<table id="resultdisplay">
			<tr>
			<td></td>
			<td></td>
			<th>Importance</th>
			</tr>
			<tr>
				<th>Market Cap</th>
				<td id="MarketCapResult"></td>
				<td><input class=slider type="range" min="0" max="100" name="sld2" value="100"></td>
			</tr>
			<tr>
				<th>Volume(24hr)</th>
				<td id="VolumeResult"></td>
				<td><input class=slider type="range" min="0" max="100" name="sld3" value="100"></td>
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
		
		<button type="button">Calculate</button>
		
		<a href="http://localhost/BigLeaf/">
		<button>New Search</button>
		</a>
	<?php else : topTenPrint(); ?>
	
	<?php endif; ?>

    </table>	
</body>
</html>
