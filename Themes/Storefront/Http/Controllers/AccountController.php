<?php

namespace Themes\Storefront\Http\Controllers;

use Modules\Media\Entities\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Transaction\Entities\Transaction;
use Modules\Order\Entities\Order;

class AccountController extends Controller
{
    public function confirmPayment(Request $request)
    {
        $file = $request->file('file');
        $path = Storage::putFile('media', $file);

        $uploadFile = File::create([
            'user_id' => auth()->id(),
            'disk' => config('filesystems.default'),
            'filename' => $file->getClientOriginalName(),
            'path' => $path,
            'extension' => $file->guessClientExtension() ?? '',
            'mime' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);

        $data['order_id'] = $request->order_id;
        $data['transaction_id'] = $request->name.', '.$request->norek.', '.$request->nominal;
        $data['payment_method'] = 1;
        $data['file'] = $uploadFile->path;

        $setData = Transaction::create($data);

        if ($setData) {
            $setOrder = Order::where('id', $request->order_id)->update(['status' => 'pending_payment']);
            return redirect()
                    ->back()
                    ->with('message', 'Persanan Berhasil Dikonfirmasi');
        }
    }
}