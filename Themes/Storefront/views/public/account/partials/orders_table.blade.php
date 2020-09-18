<div class="table-responsive">
    <table class="table table-borderless my-orders-table">
        <thead>
            <tr>
                <th>{{ trans('storefront::account.orders.order_id') }}</th>
                <th></th>
                <th>{{ trans('storefront::account.date') }}</th>
                <th>{{ trans('storefront::account.orders.status') }}</th>
                <th>{{ trans('storefront::account.orders.total') }}</th>
                <th>{{ trans('storefront::account.action') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>
                        {{ $order->id }}
                    </td>

                    <td>
                    @if($order->status() == 'Pending')
                        <span class="badge badge-success" data-toggle="modal" data-target="#confirmPaymentModal-{{ $order->id }}" style="cursor:pointer;">Konfirmasi Pembayaran</span>
                        <!-- modal -->
                        <div class="modal fade" id="confirmPaymentModal-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="confirmPaymentModalLabel">
                            <div class="modal-dialog modal-sm" role="document">
                                <div class="modal-content">
                                <form method="POST" action="{{ url('/account/confirm-payment')}}">
                                    @csrf
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                    <div class="modal-body text-left" style="padding:20px">
                                        <div class="form-group">
                                            <label for="norek">Nomor Rekening</label>
                                            <input type="number" name="norek" class="form-control" id="norek" placeholder="Nomor Rekening" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="nominal">Nominal</label>
                                            <input type="number" name="nominal" class="form-control" id="nominal" placeholder="Nominal" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Atas Nama</label>
                                            <input type="text" name="name" class="form-control" id="name" placeholder="Atas Nama" required />
                                        </div>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-small btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-small btn-primary">Submit</button>
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    </td>

                    <td>
                        {{ $order->created_at->toFormattedDateString() }}
                    </td>

                    <td>
                        <span class="badge {{ order_status_badge_class($order->status) }}">
                            {{ $order->status() }}
                        </span>
                    </td>

                    <td>
                        {{ $order->total->convert($order->currency, $order->currency_rate)->format($order->currency) }}
                    </td>

                    <td>
                        <a href="{{ route('account.orders.show', $order) }}" class="btn btn-view">
                            <i class="las la-eye"></i>
                            {{ trans('storefront::account.orders.view') }}
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
