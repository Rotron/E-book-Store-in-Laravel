<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Paypal\Paypal;
use App\Order;

class OrderController extends Controller
{

  public function callback(Paypal $paypal, Request $request)
  {
  // $paypal->postSampleDataToListener('http://996b2801.ngrok.io/storeorder', 'mc_gross=19.95&protection_eligibility=Eligible&address_status=confirmed&payer_email=sami&payer_id=LPLWNMTBWMFAY&tax=0.00&address_street=1+Main+St&payment_date=20%3A12%3A59+Jan+13%2C+2009+PST&payment_status=Completed&charset=windows-1252&address_zip=95131&first_name=Test&mc_fee=0.88&address_country_code=US&address_name=Test+User&notify_version=2.6&custom=&payer_status=verified&address_country=United+States&address_city=San+Jose&quantity=1&verify_sign=AtkOfCXbDm2hu0ZELryHFjY-Vb7PAUvS6nMXgysbElEn9v-1XcmSoGtf&payer_email=gpmac_1231902590_per%40paypal.com&txn_id=61E67681CH3238416&payment_type=instant&last_name=User&address_state=CA&receiver_email=gpmac_1231902686_biz%40paypal.com&payment_fee=0.88&receiver_id=S8XGHLYDW9T3S&txn_type=express_checkout&item_name=&mc_currency=USD&item_number=&residence_country=US&test_ipn=1&handling_amount=0.00&transaction_subject=&payment_gross=19.95&shipping=0.00');
  //  $this->storeOrder($request);
  }

  // Make sure txn_id doesn't exist in database, if it does, its duplicate POST
  // Make sure item user paid for matches its price
  public function matchPrice($itemNumber, $price)
  {
    if (Order::where('txn_id', $txnId)->first() == null) {
      Listing::where(['id' => $itemNumber, 'listing_price' => $price])->firstOrFail();
    }
  }

  // Store data from paypals callback
  public function storeOrder(Paypal $paypal, Request $request)
  {
    $data         = $paypal->formatDataCentral('stream');

    if ($payapl->postToSandbox($data) !== 'Verified') {
      throw new \Exception('Invalid payment, paypal returned failed');
    }

    $order        = new Order;
    $firstName    = $request->input('first_name');
    $lastName     = $request->input('last_name');
    $payerEmail   = $request->input('payer_email');
    $itemNumber   = $request->input('item_number');
    $userId       = $request->input('user_id');
    $txnId        = $request->input('txn_id');

    $order->payer_email = $payerEmail;
    $order->first_name  = $firstName;
    $order->last_name   = $lastName;
    $order->listing_id  = $itemNumber;
    $order->txn_id      = $txnId;
    $order->saveOrFail();
  }
}
