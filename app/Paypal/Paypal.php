<?php
  namespace App\Paypal;
  use Illuminate\Support\Facades\Storage;
  use GuzzleHttp\Client;
  use GuzzleHttp\Psr7;
  use GuzzleHttp\Exception\RequestException;
  use App\Listing;
  Use App\Transaction;

  class Paypal
  {
    const FILENAME = 'ipn_log.txt';
    private $guzzleClient;
    private $streamData;
    private $storedData;
    private $listenerUrl = 'listener';
    private $sampleData = 'mc_gross=19.95&protection_eligibility=Eligible&address_status=confirmed&payer_id=LPLWNMTBWMFAY&tax=0.00&address_street=1+Main+St&payment_date=20%3A12%3A59+Jan+13%2C+2009+PST&payment_status=Completed&charset=windows-1252&address_zip=95131&first_name=Test&mc_fee=0.88&address_country_code=US&address_name=Test+User&notify_version=2.6&custom=&payer_status=verified&address_country=United+States&address_city=San+Jose&quantity=1&verify_sign=AtkOfCXbDm2hu0ZELryHFjY-Vb7PAUvS6nMXgysbElEn9v-1XcmSoGtf&payer_email=gpmac_1231902590_per%40paypal.com&txn_id=61E67681CH3238416&payment_type=instant&last_name=User&address_state=CA&receiver_email=gpmac_1231902686_biz%40paypal.com&payment_fee=0.88&receiver_id=S8XGHLYDW9T3S&txn_type=express_checkout&item_name=&mc_currency=USD&item_number=&residence_country=US&test_ipn=1&handling_amount=0.00&transaction_subject=&payment_gross=19.95&shipping=0.00';
    private $transaction_id = 'transaction_id';
    private $amountColumn = 'amount';
    private $itemNameColumn = 'item_name';
    private $itemNumberColumn = 'id';

    function __construct()
    {
      $this->guzzleClient = new Client;

      if (!Storage::exists(self::FILENAME, '')) {
        Storage::put(self::FILENAME, '');
      }

      $this->streamData     = file_get_contents('php://input');
      $this->storedData     = Storage::get(self::FILENAME);
    }

    // Url where data is POSTed
    public function setListenerUrl($listenerUrl)
    {
      $this->listenerUrl = $listenerUrl;
    }

    public function setProductModel($productModel)
    {
      $this->productsModel = $productsModel;
    }

    public function setTransactionsTable($transactionsTable)
    {
      $this->transactionsTable = $transactionsTable;
    }

    public function setTransactionIdColumn($transactionIdColumn)
    {
      $this->transactionIdColumn = $transactionIdColumn;
    }

    public function setAmountColumn($amountColumn)
    {
      $this->amountColumn = $amountColumn;
    }

    public function setItemNameColumn($itemNameColumn)
    {
      $this->itemNameColumn = $itemNameColumn;
    }

    public function setItemNumberColumn($itemNumberColumn)
    {
      $this->itemNumberColumn = $itemNumber;
    }

    public function setSampleData($sampleData)
    {
      $this->sampleData = $sampleData;
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
    public function fakeCallbackToPaypal($callType)
    {
      if ($callType !== 'stream' and $callType !== 'stored') {
        throw new \Exception('Invalid argument, only stored and stream accepted');
      }

      if ($callType == 'stream') {
        $data = $this->formatDataCentral('stream');
        $this->postToSandbox($data);
      } else {
        $data = $this->formatDataCentral('stored');

        if (strlen($this->storedData) > 1) {
          $this->postToSandbox($data);
        } else {
          throw new \Exception('Ipn_log.txt is empty.. Post data using sample poster to fill..');
        }
      }

    }

    // Make a call to sandbox, either from stream or from storage.
    private function postToSandbox($data)
    {
      try {
          $resp = $this->guzzleClient->request('post', $url, [
            'query' => $data
          ]);
          return $resp->getBody()->getContents();
      } catch( RequestException $e) {
        echo Psr7\str($e->getRequest());
        echo Psr7\str($e->getResponse());
      }
    }


    /**
    /* Store data in ipn_log.txt file.
    /* Use IPN tester to post data to this method.
    */
    public function storeData()
    {
      self::checkStream();

      $content = file_get_contents('php://input');

      if (Storage::put(self::FILENAME, (string) $content)) {
        return true;
      }

      throw new \Exception('Failed to store data');
    }

    /**
    /* Post sample data to your listener
    /* This is sample data taken from paypal.
    /* Hence will return invalid. You can use this method to store fake data
    /* to your ipn_log.txt file. This will automatically POST the data to your
    /* listener file. From there you can use storeData() method to store it.
    /* Make sure you disable the CSRF to your listener url.
    */
    public function postSampleDataToListener()
    {
      try {
        $resp = $this->guzzleClient->request('POST', $this->listenerUrl, [
          'body' => $this->sampleData,
        ]);
        return true;

      } catch(RequestException $e) {
        echo Psr7\str($e->getRequest());
        echo Psr7\str($e->getResponse());
      }
    }


    // check if data in POST exists
    static function checkStream()
    {
      if (empty(file_get_contents("php://input"))) {
        throw new \Exception('Stream is empty. Only POST request allowed');
      }

      return file_get_contents("php://input");
    }

    /**
    /* Callback to Paypal's live server to verify transaction authenticity.
    */
    public function liveCallback($request)
    {
      self::checkStream();

      // Format the data
      $streamData = $this->formatDataCentral('stream');

      // POST back to paypals live server
      $response = $this->guzzleClient->request('post', 'https://www.paypal.com/cgi-bin/webscr', [
        'query' => $streamData
      ]);

      // Return the response
      return $response->getBody()->getContents();
    }

    /**
    /* Check that client paid right amount for the right product.
    /* Check transaction is 'Completed'
    /*
    */
    public function isCorrectPayment(Request $request)
    {
      self::checkStream();

      $paymentStatus = $request->input('status');

      if ($paymentStatus == 'Completed') {
        $price = $request->input('amount');
        $itemName = $request->input('item_name');
        $itemNumber = $request->input('item_number');
      }

      if (($price == Product::findOrFail($itemNumber)->id) &&
          ($itemName == Product::findOrFail($itemNumber)->item_name) &&
          ($itemNumber == Product::findOrFail($itemNumber)->id)) {
          return true;
      }

      return false;
    }


    /**
    /* Transaction id is always unique. If transaction id already exists in the
    /* database it means you've already processed that transaction.
    /* This method is to check exactly that.
    */
    public function isDoublePost()
    {
      self::checkStream();

      $transactionId    = $request->input('txn_id');
      $transactionMatch = Transaction::where('txn_id', $transactionId)->get();

      if (count($transactionMatch) > 1) {
        return true;
      }

      return false;
    }

    /**
    /* Store the transaction details.
    /* If same user purchases SAME product more than ones,
    /* which is checked using product_id and other info in transactions table.
    /* Then update sold quantity column. If transaction is new,
    /* then insert new record
    */
    public function storeTxn()
    {
      $transactions = new Transaction;
      $firstName    = $request->input('first_name');
      $lastName     = $request->input('last_name');
      $payerEmail   = $request->input('payer_email');
      $itemNumber   = $request->input('item_number');
      $txnId        = $request->input('txn_id');
      $listing      = Listing::find($itemNumber);

      // Only insert if email doesn't exist.
      if (!$listing->transactions->where('email', $payerEmail)->first()) {
        $transaction->email       = $payerEmail;
        $transaction->first_name  = $firstName;
        $transaction->last_name   = $lastName;;
      }

      $listing->increment('total_sold');

      $transaction->txn_id = $txnId;

      $listing->transaction()->save($transaction);
    }

    // Generate download url for paid file
    public function download($itemNumber)
    {
      $download = Listing::findOrFail($itemNumber)->listing_pdf;
      storage_path();
    }

  }


 ?>
