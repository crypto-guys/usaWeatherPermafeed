<?php
/**
 * usaWeatherPermafeed archives Current and Forecast weather data
 *
 * @category
 * @package  usaWeatherPermafeed
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
  $time = $time->format('H:00');
  $scheduleTime = $date . $time;
 /**
  * Go through list of cities and contact api for each city data
  *
  */
  $cities_file = file_get_contents('/usaWeatherPermafeed/cities.json');
  $cities = json_decode($cities_file, true);
  foreach ($cities as $city)
    {
      $lat = $city['latitude'];
      $long = $city['longitude'];
      $name = $city['city'];
      $state = $city['state'];
      $url = "https://api.openweathermap.org/data/2.5/weather?lat=$lat&lon=$long&appid=APIKEY GOES HERE";
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
          echo ('Curl Response was invalid');
      }
      /**
       * Get tag data from response
       */
      $cityName = $decoded_response['name'];
      $countryCode = $decoded_response['sys']['country'];
      $conditions = $decoded_response['weather']['0']['main'];
      /**
       * Save to arweave
       *
       */
      $arweave = new \Arweave\SDK\Arweave('https', 'arweave.net', '443');
      $jwk = json_decode(file_get_contents('/usaWeatherPermafeed/jwk.json'), true);
      $wallet = new \Arweave\SDK\Support\Wallet($jwk);
      $transaction = $arweave->createTransaction($wallet, [
                      'data' => $response,
                      'tags' => [
                          'Content-Type'  => 'application/json',
                          'Feed-Name:'    => 'usaWeatherFeed',
                          'Data-Type:'    => 'Current',
                          'Date/Time:'    => $scheduleTime,
                          'City Name:'    => $cityName,
                          'State:'        => $state,
                          'Weather:'      => $conditions,
                      ]
                  ]);
      $logFile = "/usaWeatherPermafeed/current-weather-txs.log";
      $transactionId = $transaction->getAttribute('id');
      file_put_contents($logFile, PHP_EOL . $transactionId, FILE_APPEND);
      $arweave->api()->commit($transaction);
      sleep(2); // Pause for 2 seconds to avoid flooding anyone
  }
