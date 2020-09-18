<aside class="order-summary-wrap">
    <div class="order-summary">
        <div class="order-summary-top">
            <h3 class="section-title">{{ trans('storefront::cart.order_summary') }}</h3>

            <ul class="list-inline cart-item">
                <li v-for="cartItem in cart.items">
                    <input type="hidden" class="totalQTY" :value="cartItem.qty">
                    <label>
                        <a :href="productUrl(cartItem.product)" class="product-name" v-text="cartItem.product.name"></a>
                        <span class="product-quantity" v-text="'x' + cartItem.qty"></span>
                    </label>
                    <div class="d-none">
                        <input class="totalWeight" type="text" :value="cartItem.product.weight * cartItem.qty">
                    </div>

                    <span class="price-amount" v-html="cartItem.unitPrice.inCurrentCurrency.formatted"></span>
                </li>
            </ul>
        </div>

        <div class="order-summary-middle" :class="{ loading: loadingOrderSummary }">
            <ul class="list-inline order-summary-list">
                <li>
                    <label>{{ trans('storefront::cart.subtotal') }}</label>

                    <span
                        class="price-amount"
                        v-html="cart.subTotal.inCurrentCurrency.formatted"
                    >
                    </span>
                </li>

                <li v-for="tax in cart.taxes">
                    <label v-text="tax.name"></label>

                    <span
                        class="price-amount"
                        v-html="tax.amount.inCurrentCurrency.formatted"
                    >
                    </span>
                </li>

                <li v-if="hasCoupon" v-cloak>
                    <label>
                        {{ trans('storefront::cart.coupon') }}

                        <span class="coupon-code">
                            [@{{ cart.coupon.code }}]
                            <span class="btn-remove-coupon" @click="removeCoupon">
                                <i class="las la-times"></i>
                            </span>
                        </span>
                    </label>

                    <span
                        class="price-amount"
                        v-html="'-' + cart.coupon.value.inCurrentCurrency.formatted"
                    >
                    </span>
                </li>
            </ul>

            <div class="shipping-methods" v-cloak>
                <h6>{{ trans('storefront::cart.shipping_method') }}</h6>

                <div class="form-group d-none" v-if="hasShippingMethod">
                    <div class="form-radio" v-for="shippingMethod in cart.availableShippingMethods">
                        <input
                            type="radio"
                            name="shipping_method"
                            v-model="form.shipping_method"
                            :value="shippingMethod.name"
                            :id="shippingMethod.name"
                            @change="updateShippingMethod(shippingMethod)"
                        >

                        <label :for="shippingMethod.name" v-text="shippingMethod.label"></label>

                        <span
                            class="price-amount"
                            v-html="shippingMethod.cost.inCurrentCurrency.formatted"
                        >
                        </span>
                    </div>
                </div>

                <span class="error-message d-none" v-else>
                    {{ trans('storefront::cart.shipping_method_is_not_configured') }}
                </span>

                <div id="shipping-wrapper">
                    <div class="form-group">
                        <select id="shipping-list" class="form-control arrow-black" required disabled>
                            <option value="">Pilih Kurir</option>
                        </select>
                    </div>
                    <p class="small text-danger">Anda belum melengkapi data pengiriman</p>
                </div>
            </div>

            <div class="d-none">
                <input type="text" name="default_total" class="default-total" :value="cart.total.inCurrentCurrency.formatted">
                <input type="text" name="before_shipping" class="before-shipping" :value="cart.total.inCurrentCurrency.amount">
                <input type="text" v-model="form.billing.after" name="billing[after]" class="after-shipping">
                <input type="text" v-model="form.billing.type" name="billing[type]" class="type-shipping">
                <input type="text" v-model="form.billing.cost" name="billing[cost]" class="cost-shipping">
            </div>

            <div class="order-summary-total">
                <label>{{ trans('storefront::cart.total') }}</label>
                <span class="total-price" v-html="cart.total.inCurrentCurrency.formatted"></span>
            </div>
        </div>
        @auth
        <div class="order-summary-bottom">
            <div class="form-group checkout-terms-and-conditions">
                <div class="form-check">
                    <input type="checkbox" v-model="form.terms_and_conditions" id="terms-and-conditions">

                    <label for="terms-and-conditions" class="form-check-label">
                        {{ trans('storefront::checkout.i_agree_to_the') }}
                        <a href="{{ $termsPageURL }}">
                            {{ trans('storefront::checkout.terms_&_conditions') }}
                        </a>
                    </label>

                    <span class="error-message" v-if="errors.has('terms_and_conditions')" v-text="errors.get('terms_and_conditions')"></span>
                </div>
            </div>

            <div id="paypal-button-container" v-if="form.payment_method === 'paypal'"></div>

            <button
                type="submit"
                class="btn btn-primary btn-place-order"
                :class="{ 'btn-loading': placingOrder }"
                :disabled="! form.terms_and_conditions || ! hasShippingMethod"
                v-else
                v-cloak
            >
                {{ trans('storefront::checkout.place_order') }}
            </button>
        </div>
        @else
        <p class="text-muted">Login untuk melanjutkan!</p>
        @endauth
    </div>
</aside>
