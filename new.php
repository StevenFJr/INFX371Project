<?php
    function insertmultiple($data){
        $servername = "localhost";
        $username = "user";
        $password = "12345";
        $dbname = "bigleaf";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("INSERT INTO listingslatest (id, symbol, slug, volume24h, percent_change_1h, percent_change_24h, percent_change_7d, market_cap, price) VALUES (?,?,?,?,?,?,?,?,?)");
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
        

        for($i=0;$i<=199;$i++){
            $data = [$array["data"][$i]["id"], $array["data"][$i]["symbol"], $array["data"][$i]["slug"], $array["data"][$i]["quote"]["USD"]["volume_24h"], $array["data"][$i]["quote"]["USD"]["percent_change_1h"], $array["data"][$i]["quote"]["USD"]["percent_change_24h"], $array["data"][$i]["quote"]["USD"]["percent_change_7d"], $array["data"][$i]["quote"]["USD"]["market_cap"], $array["data"][$i]["quote"]["USD"]["price"]];
            
            insertmultiple($data);
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
            prettyprint($data);
            return($data);
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
    }

    function prettyprint($array){
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
        echo("Volume 24h: ".$array[0]["volume24h"]);
        echo nl2br("\n");
        echo("Market Cap: ".$array[0]["market_cap"]);
        echo nl2br("\n");
        echo("Price: ".$array[0]["price"]);
        
    }
?>