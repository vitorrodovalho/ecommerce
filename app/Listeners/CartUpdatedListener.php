<?php

namespace App\Listeners;

use App\Cupom;
use App\Jobs\UpdateCoupon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CartUpdatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $couponName = session()->get('coupon')['name'];

        if ($couponName) {
            $coupon = Cupom::where('code', $couponName)->first();

            dispatch_now(new UpdateCoupon($coupon));
        }
    }
}
