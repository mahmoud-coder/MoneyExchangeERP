<div class="swiper slides-container">
    <div class="swiper-wrapper">
        <div class="swiper-slide customers">
            <div>
                <h5>Customers</h5>
                <small>total {!!$individual_customers_count+$entity_customers_count!!} customer</small>
                <div class="statistics">
                    <div class="boxed">{!!$individual_customers_count!!}</div> <span>Individuals</span>
                </div>
                <div class="statistics">
                    <div class="boxed">{!!$entity_customers_count!!}</div> <span>Entities</span>
                </div>
            </div>
            <div>
                <img class="illustration" src="/assets/img/illustrations/card-website-analytics-1.png">
            </div>
        </div>

        <div class="swiper-slide total-orders">
            <div>
                <h5>Total Orders</h5>
                <small>total: {!!$sell_orders_count+$buy_orders_count!!} orders</small>
                <div class="statistics">
                    <div class="boxed">{!!$sell_orders_count!!}</div> <span>Sell Orders</span>
                </div>
                <div class="statistics">
                    <div class="boxed">{!!$buy_orders_count!!}</div> <span>Buy Orders</span>
                </div>
            </div>
            <div>
                <img src="/assets/img/illustrations/card-website-analytics-2.png" class="illustration">
            </div>
        </div>

        <div class="swiper-slide total-orders">
            <div>
                <h5>Today's Orders</h5>
                <small>total: {!! $today_buy_orders_count + $today_sell_orders_count !!} order for today, ({!!$today!!})</small>
                <div class="statistics">
                    <div class="boxed">{!!$today_sell_orders_count!!}</div> <span>Sell Orders</span>
                </div>
                <div class="statistics">
                    <div class="boxed">{!!$today_buy_orders_count!!}</div> <span>Buy Orders</span>
                </div>
            </div>
            <div>
                <img src="/assets/img/illustrations/card-website-analytics-3.png" class="illustration">
            </div>
        </div>
    </div>
    <div class="swiper-pagination"></div>
</div>