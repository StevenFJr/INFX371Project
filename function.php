<?php
    function getinfo($input){        
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
?>