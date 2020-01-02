<?php
/**
 * usaWeatherFeed archives both Current and Forecast weather data
 *
 * @category
 * @package  usaWeatherFeed
 * @author   cryptoguys
 * @license  MIT
 * @link
 *
 */

 /**
  * Includes
  *
  */
 use \Arweave\SDK\Arweave;
 use \Arweave\SDK\Support\Wallet;

 include __DIR__ . '/vendor/autoload.php';

 /**
  * Date, Time, Source
  *
  */
  $source = "openWeatherMapAPI";
  $time = new DateTime(); // time right now
  $date = $time->format('Y-m-d ');

  /**
   * Go through list of cities and contact api for each city forecast data
   * api.openweathermap.org/data/2.5/forecast?lat=35&lon=139
   */
   $cities_file = file_get_contents('/usaWeatherFeed/cities.json');
   $cities = json_decode($cities_file, true);
   foreach ($cities as $city)
   {
       $lat = $city['latitude'];
       $long = $city['longitude'];
       $name = $city['city'];
       $state = $city['state'];
       $url = "https://api.openweathermap.org/data/2.5/forecast?lat=$lat&lon=$long&appid=bd7e8f874e9947b4c668726bf1e488af";
       // echo $url;
       /**
        * Use Curl to retrieve data from API source.
        */
       $curl = curl_init();
       if (!$curl)
       {
           die("Couldn't initialize a cURL handle");
       }

       curl_setopt($curl, CURLOPT_URL, $url);
       curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
       curl_setopt($curl, CURLOPT_FAILONERROR, true);
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
       curl_setopt($curl, CURLOPT_TIMEOUT, 50);
       curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
       curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

       $response = curl_exec($curl);

       if (curl_errno($curl))
       {
           echo 'cURL error: ' . curl_error($curl);
       }

       curl_close($curl);
       $decoded_response = json_decode($response, true);

       /**
        * Validate data
        */
       if (!is_array($decoded_response))
       {
           echo 'Curl Response was invalid';
       }  
       /**
        * Get tag data from response
        */

       /**
        * Save to arweave
        * 
        */
       $arweave = new \Arweave\SDK\Arweave('https', 'arweave.net', '443');
       $jwk = json_decode(file_get_contents('/usaWeatherFeed/jwk.json'), true);
       $wallet = new \Arweave\SDK\Support\Wallet($jwk);

       $transaction = $arweave->createTransaction($wallet, [
                       'data' => $response,
                       'tags' => [
                           'Content-Type'  => 'application/json',
                           'Feed-Name:'    => 'usaWeatherFeed',
                           'Data-Type:'    => 'Forecast',
                           'City Name:'    => $name,
                           'State:'        => $state,
                           'Date/Time:'    => $date,
                       ]
                   ]);

       $logFile = "/usaWeatherFeed/weather-forecast-txs.log";
       $transactionId = $transaction->getAttribute('id');

       file_put_contents($logFile, PHP_EOL . $transactionId, FILE_APPEND);

       $arweave->api()->commit($transaction);
       sleep(2); // Pause for 2 seconds to avoid flooding anyone
   }
