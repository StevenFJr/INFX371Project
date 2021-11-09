<?php
    function insertmultiple($data){
        $servername = "localhost";
        $username = "user";
        $password = "12345";
        $dbname = "bigleaf";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("INSERT INTO listingslatest (id, symbol, name, volume_24h, percent_change_1h, percent_change_24h, percent_change_7d, market_cap, price,timestamp) VALUES (?,?,?,?,?,?,?,?,?,?)");
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        try {
            $conn->beginTransaction();
            $stmt->execute($data);
            $conn->commit();
        }catch (Exception $e){
            $conn->rollback();
            throw $e;
        }
        $conn = null;
    }

    function gettopthousand(){
        if(needQuery()){
            $url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
            $parameters = [
            'convert' => 'USD',
            'start' => 1,
            'limit' => 200
            ];
            
            $headers = [
            'Accepts: application/json',
            'X-CMC_PRO_API_KEY: 1df96060-e4e1-4480-b7fa-43110a83a489'
            ];
            $qs = http_build_query($parameters); // query string encode the parameters
            $request = "{$url}?{$qs}";
        
            $curl = curl_init(); // Get cURL resource
            // Set cURL options
            curl_setopt_array($curl, array(
            CURLOPT_URL => $request,            // set the request URL
            CURLOPT_HTTPHEADER => $headers,     // set the headers 
            CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
            ));

            $response = curl_exec($curl); // Send the request, save the response       
            curl_close($curl); // Close request
            // print_r(json_decode($response,true));
            $array = json_decode($response,true);
            date_default_timezone_set("EST");
            $timestamp=date_timestamp_get(date_create());

            cleardb();

            for($i=0;$i<=199;$i++){
                $data = [$array["data"][$i]["id"], $array["data"][$i]["symbol"], $array["data"][$i]["name"], $array["data"][$i]["quote"]["USD"]["volume_24h"], $array["data"][$i]["quote"]["USD"]["percent_change_1h"], $array["data"][$i]["quote"]["USD"]["percent_change_24h"], $array["data"][$i]["quote"]["USD"]["percent_change_7d"], $array["data"][$i]["quote"]["USD"]["market_cap"], $array["data"][$i]["quote"]["USD"]["price"],$timestamp];
                
                insertmultiple($data);
            }
        }
    }


    function query($parameter, $search){
        $servername = "localhost";
        $username = "user";
        $password = "12345";
        $dbname = "bigleaf";
        
        try{
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT * FROM listingslatest WHERE $parameter = '$search' ORDER BY market_cap DESC");
            $stmt->execute();
        
            // set the resulting array to associative
            $data = $stmt->fetchAll();
            if(empty($data)){
                throw new Exception();
            }
            printCoin($data);
            return($data);
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
    }

    function printCoin($array){
        echo("<img src='https://s2.coinmarketcap.com/static/img/coins/64x64/".$array[0]["id"].".png' alt='Coin Image'>");
        echo nl2br("\n");
        echo("ID: ".$array[0]["id"]);
        echo nl2br("\n");
        echo("Symbol: ".$array[0]["symbol"]);
        echo nl2br("\n");
        echo("Name: ".$array[0]["slug"]);
        echo nl2br("\n");
        echo("Percent Change 1h: ".$array[0]["percent_change_1h"]);
        echo nl2br("\n");
        echo("Percent Change 24h: ".$array[0]["percent_change_24h"]);
        echo nl2br("\n");
        echo("Percent Change 7d: ".$array[0]["percent_change_7d"]);
        echo nl2br("\n");
        echo("Volume 24h: ".$array[0]["volume_24h"]);
        echo nl2br("\n");
        echo("Market Cap: ".$array[0]["market_cap"]);
        echo nl2br("\n");
        echo("Price: ".$array[0]["price"]);
        
    }
	
	function topTenPrint(){
        $servername = "localhost";
        $username = "user";
        $password = "12345";
        $dbname = "bigleaf";
        
        try{
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT * FROM listingslatest ORDER BY market_cap DESC LIMIT 10");
            $stmt->execute();
        
            // set the resulting array to associative
            $data = $stmt->fetchAll();
            if(empty($data)){
                throw new Exception();
            }
			for($i=0;$i<=9;$i++){
				echo nl2br("\n");
				echo nl2br("\n");
				echo("<img src='https://s2.coinmarketcap.com/static/img/coins/32x32/".$data[$i]["id"].".png' alt='Coin Image'>");
				echo("\r\n");
				echo("<h3 style='display:inline'>ID: </h3><p style='display:inline'>".$data[$i]["id"]."</p>");
				echo("\r\n");
				echo("<h3 style='display:inline'>Symbol: </h3><p style='display:inline'>".$data[$i]["symbol"]."</p>");
				echo("\r\n");
				echo("<h3 style='display:inline'>Name: </h3><p style='display:inline'>".$data[$i]["name"]."</p>");
				echo("\r\n");
				echo("<h3 style='display:inline'>Percent Change 1h: </h3><p style='display:inline'>".$data[$i]["percent_change_1h"]."</p>");
				echo("\r\n");
				echo("<h3 style='display:inline'>Percent Change 24h: </h3><p style='display:inline'>".$data[$i]["percent_change_24h"]."</p>");
				echo("\r\n");
				echo("<h3 style='display:inline'>Percent Change 7d: </h3><p style='display:inline'>".$data[$i]["percent_change_7d"]."</p>");
				echo("\r\n");
				echo("<h3 style='display:inline'>Volume 24h: </h3><p style='display:inline'>".$data[$i]["volume_24h"]."</p>");
				echo("\r\n");
				echo("<h3 style='display:inline'>Market Cap: </h3><p style='display:inline'>".$data[$i]["market_cap"]."</p>");
				echo("\r\n");
				echo("<h3 style='display:inline'>Price: </h3><p style='display:inline'>".$data[$i]["price"]."</p>");
				echo nl2br("\n"."<hr>");
			}
            $conn = null;
            return($data);
        } catch(PDOException $e) {
            $conn = null;
            echo "Error: " . $e->getMessage();
        }
    }

    function cleardb(){
        $servername = "localhost";
        $username = "user";
        $password = "12345";
        $dbname = "bigleaf";
        
        try{
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("DELETE FROM listingslatest");
            $stmt->execute();
        }catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
    }

    function needQuery(){
        $servername = "localhost";
        $username = "user";
        $password = "12345";
        $dbname = "bigleaf";
        
        try{
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT timestamp FROM listingslatest LIMIT 1");
            $stmt->execute();
            $data = $stmt->fetch();
        }catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
        if(date_timestamp_get(date_create()) - $data[0] >=30){
            return true;
        }else{
            return false;
        }
    }
?>
