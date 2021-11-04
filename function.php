<?php
    function query(){
        $servername = "localhost";
        $username = "user";
        $password = "12345";
        $dbname = "bigleaf";
        
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT name, symbol FROM listingslatest");
            $stmt->execute();
        
            // set the resulting array to associative
            $symbols = $stmt->fetchAll();
            return($symbols);
        } catch(PDOException $e) {
          echo "Error: " . $e->getMessage();
        }
        $conn = null;
    }
    
    function getinfoname($input){        
        $url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest';
        $parameters = [
        'slug' => strtolower($input),
        'convert' => 'USD'
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
        return(json_encode($response));
    }
  
    function getinfosymbol($input){        
        $url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest';
        $parameters = [
        'symbol' => $input,
        'convert' => 'USD'
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
        return(json_encode($response));
    }

    function gettopten(){
        $url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
        $parameters = [
        'convert' => 'USD',
        'start' => 1,
        'limit' => 10
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

        return($response);
    }


    
?>