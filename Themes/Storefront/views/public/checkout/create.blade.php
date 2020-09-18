@extends('public.layout')

@section('title', trans('storefront::checkout.checkout'))

@section('content')
    <checkout-create
        :gateways="{{ $gateways }}"
        :countries="{{ json_encode($countries) }}"
        inline-template
    >
        <section class="checkout-wrap">
            <div class="container">
                @include('public.cart.index.steps')

                <form @submit.prevent="placeOrder" @input="errors.clear($event.target.name)" id="formCheckout">
                    <div class="checkout">
                        <div class="checkout-inner">
                            <div class="checkout-left">
                                <div class="checkout-form">
                                    @include('public.checkout.create.form.account_details')
                                    @include('public.checkout.create.form.billing_details')
                                    @include('public.checkout.create.form.shipping_details')
                                    @include('public.checkout.create.form.order_note')
                                </div>
                            </div>

                            <div class="checkout-right">
                                @include('public.checkout.create.payment')
                                @include('public.checkout.create.coupon')
                            </div>
                        </div>

                        @include('public.checkout.create.order_summary')
                    </div>
                </form>
            </div>
        </section>
    </checkout-create>
    <div class="custom-loader hide"><span>Sedang mengambil data..</span></div>
@endsection

@push('pre-scripts')
    @if (setting('paypal_enabled'))
        <script src="https://www.paypal.com/sdk/js?client-id={{ setting('paypal_client_id') }}&currency={{ setting('default_currency') }}&disable-funding=credit,card,venmo,sepa,bancontact,eps,giropay,ideal,mybank,p24,p24"></script>
    @endif

    @if (setting('stripe_enabled'))
        <script src="https://js.stripe.com/v3/"></script>
    @endif

    @if (setting('razorpay_enabled'))
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    @endif
@endpush

@push('scripts')
    <script>
        let setState = $('#billing-state');
        let setCity = $('#billing-city');
        let shippingList = $('#shipping-list');
        let shippingAlert = $('#shipping-wrapper p');
        let defaultWeight = 300;
        let totalProduct = 0;
        let totalWeight = 0;
        $(".totalQTY").each( function() { 
            totalProduct += +this.value;
        });

        $(".totalWeight").each( function() { 
            totalWeight += +this.value;
        });

        // get city by province
        function getCity(param) {
            $.ajax({
                url: "{{ url('/ongkir/get/city?province=') }}" + param,
                type:'GET',
                dataType: 'json',
                success: function(response) {
                    setCity.attr('disabled', false);
                    $.each(response,function(key, value) {
                        setCity.append('<option value=' + value.city_id + '>' + value.type + ' ' + value.city_name + '</option>');
                    });
                    $('.custom-loader').addClass('hide');
                }
            });
        }

        // get ongkir
        function getCost(cityId) {
            let setWeight = totalWeight ? totalWeight : defaultWeight;
            $.ajax({
                url: "{{ url('/ongkir?destination=') }}" + cityId + '&weight=' + setWeight,
                type:'GET',
                dataType: 'json',
                success: function(response) {
                    $.each(response,function(key, value) {
                        let optgroup = '<optgroup label="' + value.code.toUpperCase() +'">';

                        $.each(value.costs,function(key, item) {
                            optgroup += '<option value="'+ item.cost[0].value +'">' + value.code.toUpperCase() + ' ' + item.service + ' - IDR' + item.cost[0].value + '</option>';
                        });

                        optgroup += "</optgroup>"

                        shippingList.append(optgroup);
                    });
                    $('.custom-loader').addClass('hide');
                }
            });
        }

        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        }

        function clearSummary() {
            $('.after-shipping').val($('.before-shipping').val());
            $('.type-shipping').val('');
            $('.cost-shipping').val('');
            $('.total-price').text($('.default-total').val());
        }

        $(document).ready(function() {
            if (setState.val()) {
                getCity(setState.val());   
            }
        });

        setState.on('change', function() {
            clearSummary()
            shippingList.attr('disabled', true);
            shippingAlert.show();
            setCity.attr('disabled', true);
            setCity.html('<option value="">Please Select</option>');
            if ($(this).val()) {
                getCity($(this).val());
                $('.custom-loader').removeClass('hide');
            }
        });

        setCity.on('change', function() {
            clearSummary()
            if ($(this).val()) {
                shippingList.attr('disabled', false);
                shippingList.html('<option value="">Pilih Kurir</option>');
                shippingAlert.hide();
                getCost($(this).val());
                $('.custom-loader').removeClass('hide');
            } else {
                shippingList.attr('disabled', true);
                shippingAlert.show();
            }
        });

        shippingList.on('change', function() {
            var selectedText = $(this).find("option:selected").text();
            var selectedValue = $(this).val();

            if (selectedValue) {
                let withShipping = parseInt($('.before-shipping').val()) + parseInt(selectedValue);
                $('.after-shipping').val(parseFloat(withShipping).toFixed(4))[0].dispatchEvent(new Event('input', { 'bubbles': true }));
                $('.type-shipping').val(selectedText.split('-')[0])[0].dispatchEvent(new Event('input', { 'bubbles': true }));
                $('.cost-shipping').val(parseFloat(selectedValue).toFixed(4))[0].dispatchEvent(new Event('input', { 'bubbles': true }));
                $('.total-price').text('IDR' + formatNumber(withShipping));
            } else {
                clearSummary()
            }
        })

    </script>
@endpush