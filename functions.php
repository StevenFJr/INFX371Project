<?php
    if (!function_exists('str_contains')) {         //create str_contains if it doesnt exist
        function str_contains($haystack, $needle) {
            return $needle !== '' && mb_strpos($haystack, $needle) !== false;
        }
    }
    function savequery($type,$input){       //this function saves user queries as the searched symbol or name with timestamp. Saves query as failedsearch if the request does not exist or isnt in top 1000
        $servername = "localhost";
        $username = "user";
        $password = "12345";
        $dbname = "bigleaf";
        date_default_timezone_set("EST");
        $timestamp=date_timestamp_get(date_create());
        $data=array($timestamp,$input);

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if($type == "symbol"){
                $stmt = $conn->prepare("INSERT INTO searches (timestamp, symbol) VALUES (?,?)");
            } else if($type == "name"){
                $stmt = $conn->prepare("INSERT INTO searches (timestamp, name) VALUES (?,?)");
            }else{
                $stmt = $conn->prepare("INSERT INTO searches (timestamp, failedsearch) VALUES (?,?)");
            }
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

    function insertmultiple($data){         //inserts coins into listings table
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

    function gettopthousand(){      //querys api for top 1000 coins (adjust limit number to adjust number of coins queried)
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

    function APICall($parameter,$search){ //no longer used but can be used to query for a specific coin. Varibles $parameter = "name" or "symbol" $search = the name or symbol of the particular coin 
        $url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest';
        $parameters = [
        'convert' => 'USD',
        $parameter => $search,
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
        
        if(str_contains($response,"Invalid") || empty($response)){
            throw new Exception();
        }else{
            $array=(json_decode($response,true));
            echo("<img src='https://s2.coinmarketcap.com/static/img/coins/64x64/".$array["data"][$search]["id"].".png' alt='Coin Image'>");
            echo nl2br("\n");
            // echo("ID: ".$array["data"][$search]["id"]);
            // echo nl2br("\n");
            echo("Symbol: ".$array["data"][$search]["symbol"]);
            echo nl2br("\n");
            echo("Name: ".$array["data"][$search]["name"]);
            echo nl2br("\n");
            echo("Price: ".$array["data"][$search]["quote"]["USD"]["price"]);    
            echo nl2br("\n");
            echo("Percent Change 1h: ".$array["data"][$search]["quote"]["USD"]["percent_change_1h"]);
            echo nl2br("\n");
            echo("Percent Change 24h: ".$array["data"][$search]["quote"]["USD"]["percent_change_24h"]);
            echo nl2br("\n");
            echo("Percent Change 7d: ".$array["data"][$search]["quote"]["USD"]["percent_change_7d"]);
            echo nl2br("\n");
            echo("Volume 24h: ".$array["data"][$search]["quote"]["USD"]["volume_24h"]);
            echo nl2br("\n");
            echo("Market Cap: ".$array["data"][$search]["quote"]["USD"]["market_cap"]);
                
        }
    }
    function query($parameter, $search){ //get coin data from the db. Varibles $parameter = "name" or "symbol" $search = the name or symbol of the particular coin
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

    function printCoin($array){ //prints coin data. called by query function
        echo("<img src='https://s2.coinmarketcap.com/static/img/coins/64x64/".$array[0]["id"].".png' alt='Coin Image'>");
        echo nl2br("\n");        
        
        echo("Symbol: ".$array[0]["symbol"]);
        echo nl2br("\n");
        echo("Name: ".$array[0]["name"]);
        echo nl2br("\n");
        echo("Price: ".$array[0]["price"]);
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
        
        
    }
	
	function topTenPrint(){ //prints the data from the top ten coins. called in index.php
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
				// echo("<h3 style='display:inline'>ID: </h3><p style='display:inline'>".$data[$i]["id"]."</p>");
				echo("\r\n");
				echo("<h3 style='display:inline'>Symbol: </h3><p style='display:inline'>".$data[$i]["symbol"]."</p>");
				echo("\r\n");
				echo("<h3 style='display:inline'>Name: </h3><p style='display:inline'>".$data[$i]["name"]."</p>");
				echo("\r\n");
                echo("<h3 style='display:inline'>Price: </h3><p style='display:inline'>".$data[$i]["price"]."</p>");
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
				echo nl2br("\n"."<hr>");
			}
            $conn = null;
            return($data);
        } catch(PDOException $e) {
            $conn = null;
            echo "Error: " . $e->getMessage();
        }
    }

    function cleardb(){ //clears out the old listingslatest table data
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

    function needQuery(){ //determines if data in listingslatest table is too old and needs to be requeried
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

    function TopQuery(){ //finds top coin based on marketcap
        $servername = "localhost";
        $username = "user";
        $password = "12345";
        $dbname = "bigleaf";
        
        try{
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT * FROM listingslatest ORDER BY market_cap DESC LIMIT 1");
            $stmt->execute();
        
            // set the resulting array to associative
            $data = $stmt->fetchAll();
            if(empty($data)){
                throw new Exception();
            }
            return($data);
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
    }

    function calc($MrkW,$VolW,$C1W,$C24W,$C7W,$array){ //performs math on target coin data ($array) using weights passed in by calc.php to determine grade of coin as compared to top coin
        $topcoin=TopQuery();
        $topMrk=$topcoin[0]["market_cap"];
        $topVol=$topcoin[0]["volume_24h"];
        $topC1=$topcoin[0]["percent_change_1h"];
        $topC24=$topcoin[0]["percent_change_24h"];
        $topC7=$topcoin[0]["percent_change_7d"];

        $total = $MrkW+$VolW+$C1W+$C24W+$C7W;
        $Mrk=$array[0]["market_cap"];
        $Vol=$array[0]["volume_24h"];
        $C1=$array[0]["percent_change_1h"];
        $C24=$array[0]["percent_change_24h"];
        $C7=$array[0]["percent_change_7d"];


        $MrkW=$MrkW/$total;
        $VolW=$VolW/$total;
        $C1W=$C1W/$total;
        $C24W=$C24W/$total;
        $C7W=$C7W/$total;
    
        echo nl2br("\n");
        echo nl2br("\n");
        //The next 104 lines of code (342-426) are math for grading change in 1h, 24h, and 7d
            if(($topC1>0 && $C1>0)){ //both positive test
                if($C1 > $topC1){
                    $C1Res = (1 * $C1W) * 100;
                    echo ("C1: ".$C1Res);
                    echo nl2br("\n");
                }else{
                    $C1Res = (($C1/$topC1) * $C1W) * 100;
                    echo ("C1: ".$C1Res);
                    echo nl2br("\n");
                }
            }else if($topC1<0 && $C1<0){ //both negative test
                if($C1 > $topC1){
                    $C1Res = (1 * $C1W) * 100;
                    echo ("C1: ".$C1Res);
                    echo nl2br("\n");
                }else{
                    $C1Res = (($topC1/$C1) * $C1W) * 100;
                    echo ("C1: ".$C1Res);
                    echo nl2br("\n");
            }
            }else if($topC1<0){ //if top coin is negative and comparing coin isnt
                $C1Res = (1 * $C1W) * 100;
                echo ("C1: ".$C1Res);
                echo nl2br("\n");
            } else{ // if comparing coin is negative and top coin isnt
                $C1Res = 0;
                echo ("C1: ".$C1Res);
                echo nl2br("\n");
            }
            if($topC24>0 && $C24>0){ //both pos
                if($C24 > $topC24){
                    $C24Res = (1 * $C24W) * 100;
                    echo ("C24: ".$C24Res);
                    echo nl2br("\n");
                }else{
                    $C24Res = (($C24/$topC24) * $C24W) * 100;
                    echo ("C24: ".$C24Res);
                    echo nl2br("\n");
                }
            }else if($topC24<0 && $C24<0){ //both neg
                if($C24 > $topC24){
                    $C24Res = (1 * $C24W) * 100;
                    echo ("C24: ".$C24Res);
                    echo nl2br("\n");
                }else{
                    $C24Res = (($topC24/$C24) * $C24W) * 100;
                    echo ("C24: ".$C24Res);
                    echo nl2br("\n");
                }
            }else if($topC24<0){ //top neg comp pos
                $C24Res = (1 * $C24W) * 100;
                echo ("C24: ".$C24Res);
                echo nl2br("\n");
            } else{ //top pos comp neg
                $C24Res = 0;
                echo ("C24: ".$C24Res);
                echo nl2br("\n");
            }
            if($topC7>0 && $C7>0){ //both pos
                if($C7 > $topC7){
                    $C7Res = (1 * $C7W) * 100;
                    echo ("C7: ".$C7Res);
                    echo nl2br("\n");
                }else{
                    $C7Res = (($C7/$topC7) * $C7W) * 100;
                    echo ("C7: ".$C7Res);
                    echo nl2br("\n");
                }
            }else if($topC7<0 && $C7<0){ //both neg
                if($C7 > $topC7){
                    $C7Res = (1 * $C7W) * 100;
                    echo ("C7: ".$C7Res);
                    echo nl2br("\n");
                }else{
                    $C7Res = (($topC7/$C7) * $C7W) * 100;
                    echo ("C7: ".$C7Res);
                    echo nl2br("\n");
                }
            }else if($topC7<0){ //top neg comp pos
                $C7Res = (1 * $C7W) * 100;
                echo ("C7: ".$C7Res);
                echo nl2br("\n");
            } else{ //top pos comp neg
                $C7Res = 0;
                echo ("C7: ".$C7Res);
            }
        
            //These if-else statements determine the score of market cap 
            if($Mrk>=10000000000){
                $MrkRes= (1*$MrkW)*100;
            }else if($Mrk<10000000000 && $Mrk >= 1000000000){
                $MrkRes= (.80*$MrkW)*100;
            }else if($Mrk<1000000000 && $Mrk >= 500000000){
                $MrkRes= (.40*$MrkW)*100;
            }else{
                $MrkRes= (0*$MrkW)*100;
            }

            //This code determines score of volume24





        $score=$C1Res+$C24Res+$C7Res+$MrkRes;
        // echo($MrkW*100+$VolW*100+$C1W*100+$C24W*100+$C7W*100);
    
        if($score>=90){
            echo("This coin is performing very well");
        }else if($score>=80){
            echo("This coin is performing above average."); 
        }else if($score>=70){
            echo("This coin is performing average.");
        }else if($score>=60){
            echo("This coin is performing below average.");
        }else{
            echo("This coin is performing poorly.");
        }
    }
?>
