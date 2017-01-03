<?php
  namespace App;
  use Illuminate\Support\Facades\Storage;
  use GuzzleHttp\Client;

  class PaypalIPN
  {
    const FILENAME = 'ipn_log.txt';
    protected $streamData;
    protected $storedData;
    protected $guzzleClient;

    function __construct()
    {
      if (!Storage::exists(self::FILENAME, '')) {
        Storage::put(self::FILENAME, '');
      }

      $this->streamData = file_get_contents('php://input');

      $this->storedData = Storage::get(self::FILENAME);

      $this->guzzleClient = new Client;
    }


    /**
    /* Converts the querystring to usable key => value array()
    /* adds 'cmd=_notify-validate' to it.
    /* Always uses ipn_log.txt file as source of data.
    /* Used by formatDataCentral() method
    */
    private function dataFormatter($data)
    {
      $formattedData = 'cmd=_notify-validate';

      foreach ($data as $key => $val) {
        $formattedData .= "&$key=" . urlencode($val);
      }

      dd($formattedData);

      return $formattedData;
    }


    /**
    /* Formats data either from:
    /* 'stream': php://input, 'stored': ipn_log.txt
    /* Formatting from stream is used for live callback() method
    /* Formatting from stored is always used for tests
    */
    public function formatDataCentral($formatType)
    {
      if (($formatType !== 'stream') and ($formatType !== 'stored')) {
        throw new \Exception('raw or stored are only accepted arguments');
      }

      if ($formatType ==  'stored') {
        parse_str($this->storedData, $storedToArray);
        return $this->dataFormatter($storedToArray);
      }

      if ($formatType == 'stream') {
        parse_str($this->streamData, $streamToArray);
        return $this->dataFormatter($streamToArray);
      }
    }


    /**
    /* Display query string or an array stored in the ipn_log.txt
    /* Accepts as arguement 'raw' or 'array'
    /* 'raw' will return the query string strored in ipn_log.txt
    /* 'array' will return a properly formatted array
    */
    public function displayData($requestDataType)
    {
      if ($requestDataType !== 'raw' && $requestDataType !== 'array') {
        throw new \Exception('Only raw or array arguments accpted');
      }

      // display stored data as is.
      if (strlen($this->storedData) > 1) {

        if ($requestDataType == 'raw') {
            return $this->storedData;
        }

        // display stored data as array
        if ($requestDataType == 'array') {
          parse_str($this->storedData, $requestArray);
            return $requestArray;
        };

      }
    }


    /**
    /* Make a test call to paypal using querystring from ipn_log.txt
    /* ipn_log.txt must already have the querystring to postback.
    /* If ipn_log.txt doesn't have querystring make
    /* a test IPN request from sandbox to this storeData() method.
    */
    public function fakeCallbackToPaypal()
    {
      $data = $this->formatDataCentral('i');

      if (strlen($this->storedData) > 1) {
        $resp = $this->guzzleClient->request('post', 'https://www.sandbox.paypal.com/cgi-bin/webscr', [
          'query' => $data
        ]);

        return $resp->getBody()->getContents();
      }
    }


    /**
    /* Store data in ipn_log.txt file.
    /* Use IPN tester to post data to this method.
    */
    public function storeData()
    {
      $content = file_get_contents('php://input');

      if (Storage::put(self::FILENAME, (string) $content)) {
        return true;
      }

      return false;
    }


    public function getVal()
    {
      
    }

    /**
    /* Callback to Paypal's live server to verify transaction autnenticity.
    */
    public function liveCallback($request)
    {
      // if (empty(file_get_contents("php://input"))) {
      //   throw new \Exception('Stream is empty');
      // }

      $streamData = $this->formatDataCentral('stream');

      dd($streamData);
      $response = $this->guzzleClient->request('post', 'https://www.paypal.com/cgi-bin/webscr', [
        'query' => $streamData
      ]);

      return $response->getBody()->getContents();
    }

  }

 ?>
