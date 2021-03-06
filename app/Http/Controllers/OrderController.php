<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Paypal\Paypal;
use App\Order;
use App\Listing;
use Auth;

class OrderController extends Controller
{
  public function __construct(Paypal $paypal, Request $request)
  {
    $this->firstName      = $request->input('first_name');
    $this->lastName       = $request->input('last_name');
    $this->payerEmail     = $request->input('payer_email');
    $this->receiverEmail  = $request->input('receiver_email');
    $this->itemNumber     = $request->input('item_number');
    $this->mcGross        = $request->input('mc_gross');
    $this->userId         = $request->input('custom');
    $this->txnId          = $request->input('txn_id');
    $this->status         = $request->input('payment_status');
    $this->request        = $request;
    $this->paypal         = $paypal;
  }

  // Make callback to paypal
  public function callbackPaypal()
  {
    $data = $this->paypal->formatDataCentral('stream');
    $this->paypal->postToSandbox($data);
    $this->paidToRightEmail();
    $this->matchPrice();
    $this->checkIfDuplicateTxn();
    $this->storeOrder();
  }

  /**
  * Make sure user pays right amount for right item.
  * returns exception if wrong price for wrong item..
  */
  public function matchPrice()
  {
    if (Listing::where(['id' => $this->itemNumber, 'listing_price' => $this->mcGross]) == null) {
      throw new \Exception('User paid wrong amount for the item');
    }
  }


  /**
  * txn_id cant already be in database, if it's there
  * it means it's duplicate transaction
  * @returns true/false
  */
  public function checkIfDuplicateTxn()
  {
    if (Order::where('txn_id', $this->txnId)->first() != null) {
      throw new \Exception('Transaction ID already exists in database, duplicate transaction');
    }
  }


  /**
  * Make sure user sent the money to your email defined in .env file
  */
  public function paidToRightEmail()
  {
    if( config('app.paypal_email') != $this->receiverEmail) {
      throw new \Exception('Payment was made to wrong email' . $this->receiverEmail);
    }
  }

  // Store data from paypals callback
  public function storeOrder()
  {
    $order              = new Order;
    $order->payer_email = $this->payerEmail;
    $order->first_name  = $this->firstName;
    $order->last_name   = $this->lastName;
    $order->listing_id  = $this->itemNumber;
    $order->mc_gross    = $this->mcGross;
    $order->txn_id      = $this->txnId;
    $order->status      = $this->status;
    $order->user_id     = $this->userId;

    $order->saveOrFail();
  }

}
