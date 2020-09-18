<?php

namespace Themes\Storefront\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Transaction\Entities\Transaction;
use Modules\Order\Entities\Order;

class AccountController extends Controller
{
    public function confirmPayment(Request $request)
    {
        $data['order_id'] = $request->order_id;
        $data['transaction_id'] = $request->name.', '.$request->norek.', '.$request->nominal;
        $data['payment_method'] = 1;

        $setData = Transaction::create($data);

        if ($setData) {
            $setOrder = Order::where('id', $request->order_id)->update(['status' => 'pending_payment']);
            return redirect()
                    ->back()
                    ->with('message', 'Persanan Berhasil Dikonfirmasi');
        }
    }
}