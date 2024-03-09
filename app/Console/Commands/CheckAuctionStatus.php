<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Notifications\ClosingAuctionNotification;
use App\Notifications\WinningAuctionNotification;
use Illuminate\Console\Command;

class CheckAuctionStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auctions:check-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check auction status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // get all products that are not sold and end time is less than current time, where item listing type is auction
        $auctions = \App\Models\Product::whereHas('productListing', function ($query) {
            $query->where('item_type', 'auction');
            // ->where('end_time', '<=', date('Y-m-d H:i:s'));
        })->where('is_sold', 0)
            ->get();

        // dd($auctions);

        foreach ($auctions as $auction) {
            $listing = $auction->productListing;
            $pricing = $auction->productPricing;
            $bids = $auction->bids;

            if ($listing->reserved) {
                if ($listing->end_time > date('Y-m-d H:i:s')) {
                    if ($bids->count() > 0) {
                        // the bid with the highest price
                        $highest_price = $bids->max('price');
                        $highest_bid = $bids->where('price', $highest_price)->first();

                        // check if highest price is equal or greater than reserve price
                        if ($highest_price >= $pricing->reserve_price) {
                            // update item listing to sold
                            $listing->update([
                                'closed' => 1,
                                'closed_at' => date('Y-m-d H:i:s'),
                                'sold' => 1,
                                'highest_bid_id' => $highest_bid->id,
                                'winner_id' => $highest_bid->user_id
                            ]);

                            // declined all other bids
                            \App\Models\Bid::where('id', '!=', $highest_bid->id)->where('product_id', $auction->id)->update([
                                'status' => 'declined',
                            ]);

                            // make winner the winner bid
                            $highest_bid->update([
                                'status' => 'winner',
                            ]);


                            // create the order for the winning bid
                            $price = $highest_price;
                            $quantity = 1;
                            $insurance = 0;
                            $shipping_cost = 0;
                            $total = ($price * $quantity) + $shipping_cost + $insurance;

                            $createdOrder = \App\Models\Order::create([
                                'user_id' => $highest_bid->user_id,
                                'store_id' => $auction->store_id,
                                'postal_insurance' => $insurance,
                                'shipping_cost' => $shipping_cost,
                                'total' => $total,
                                'status' => 'pending',
                                'payment_status' => 'pending',
                                'won_via' => 'auction',
                            ]);
                            if ($createdOrder) {
                                // also create order items
                                \App\Models\OrderItem::create([
                                    'order_id' => $createdOrder->id,
                                    'product_id' => $auction->id,
                                    'quantity' => $quantity,
                                    'price' => $price,
                                ]);

                                $auction->store->update([
                                    'closed_auctions' => $auction->store->closed_auctions + 1,
                                ]);

                                // send notification to winning user
                                $highest_bid->user->notify(new WinningAuctionNotification($auction));


                                // save notification to database
                                Notification::create([
                                    'user_id' => $highest_bid->user_id,
                                    'title' => 'You won an auction for the item: ' . $auction->name,
                                    'is_read' => 0,
                                    // 'source' =>
                                ]);

                                // save notification to database
                                Notification::create([
                                    'user_id' => $auction->store->user_id,
                                    'title' => 'Your item: ' . $auction->name . ' has been sold.',
                                    'is_read' => 0,
                                    // 'source' =>
                                ]);
                            }
                        }
                    }
                } else {
                    // check for any bids on the product
                    if ($bids->count() == 0) {
                        if ($listing->relisted == $listing->relist_limit) {
                            // update item listing to closed
                            $listing->update([
                                'closed' => 1,
                                'closed_at' => date('Y-m-d H:i:s'),
                            ]);

                            $auction->store->update([
                                'closed_auctions' => $auction->store->closed_auctions + 1,
                            ]);

                            // send notification to seller that auction is closed
                            $auction->store->user->notify(new ClosingAuctionNotification($auction));

                            // save notification to database
                            Notification::create([
                                'user_id' => $auction->store->user_id,
                                'title' => 'Your auction for the item: ' . $auction->name . ' has been closed without selling.',
                                'is_read' => 0,
                                // 'source' =>
                            ]);
                        } else {
                            // get start time
                            $startTime = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
                            // extract only days number value from duration string (e.g. 7 days)
                            $duration = substr($listing->duration, 0, strpos($listing->duration, ' '));
                            // obtain endTime from startTime and duration
                            $endTime = \Carbon\Carbon::parse($startTime)->addDays($duration)->format('Y-m-d H:i:s');

                            // update item listing to buy-it-now type
                            $listing->update([
                                'item_type' => 'buy-it-now',
                                'relisted' => $listing->relisted + 1,
                                'start_time' => $startTime,
                                'end_time' => $endTime,
                                'reserved' => false
                            ]);

                            // update item pricing for buy-it-now type
                            $pricing->update([
                                'buy_it_now_price' => $pricing->reserve_price,
                            ]);

                            $auction->store->update([
                                'buyitnow_items' => $auction->store->buyitnow_items + 1,
                                'closed_auctions' => $auction->store->closed_auctions + 1,
                            ]);

                            // send notification to seller that auction is closed
                            $auction->store->user->notify(new ClosingAuctionNotification($auction));

                            // save notification to database
                            Notification::create([
                                'user_id' => $auction->store->user_id,
                                'title' => 'Your auction for the item: ' . $auction->name . ' has been closed without selling.',
                                'is_read' => 0,
                                // 'source' =>
                            ]);

                            // save notification to database
                            Notification::create([
                                'user_id' => $auction->store->user_id,
                                'title' => 'Your item: ' . $auction->name . ' is relisted as buy it now.',
                                'is_read' => 0,
                                // 'source' =>
                            ]);
                        }
                    } else {
                        // the bid with the highest price
                        $highest_price = $bids->max('price');
                        $highest_bid = $bids->where('price', $highest_price)->first();

                        // check if highest price is equal or greater than reserve price
                        if ($highest_price >= $pricing->reserve_price) {
                            // update item listing to sold
                            $listing->update([
                                'closed' => 1,
                                'closed_at' => date('Y-m-d H:i:s'),
                                'sold' => 1,
                                'highest_bid_id' => $highest_bid->id,
                                'winner_id' => $highest_bid->user_id
                            ]);

                            // declined all other bids
                            \App\Models\Bid::where('id', '!=', $highest_bid->id)->where('product_id', $auction->id)->update([
                                'status' => 'declined',
                            ]);

                            // make winner the winner bid
                            $highest_bid->update([
                                'status' => 'winner',
                            ]);

                            // create the order for the winning bid
                            $price = $highest_price;
                            $quantity = 1;
                            $insurance = 0;
                            $shipping_cost = 0;
                            $total = ($price * $quantity) + $shipping_cost + $insurance;

                            $createdOrder = \App\Models\Order::create([
                                'user_id' => $highest_bid->user_id,
                                'store_id' => $auction->store_id,
                                'postal_insurance' => $insurance,
                                'shipping_cost' => $shipping_cost,
                                'total' => $total,
                                'status' => 'pending',
                                'payment_status' => 'pending',
                                'won_via' => 'auction',
                            ]);
                            if ($createdOrder) {
                                // also create order items
                                \App\Models\OrderItem::create([
                                    'order_id' => $createdOrder->id,
                                    'product_id' => $auction->id,
                                    'quantity' => $quantity,
                                    'price' => $price,
                                ]);

                                $auction->store->update([
                                    'closed_auctions' => $auction->store->closed_auctions + 1,
                                ]);

                                // send notification to winning user
                                $highest_bid->user->notify(new WinningAuctionNotification($auction));

                                // save notification to database
                                Notification::create([
                                    'user_id' => $highest_bid->user_id,
                                    'title' => 'You won an auction for the item: ' . $auction->name,
                                    'is_read' => 0,
                                    // 'source' =>
                                ]);

                                Notification::create([
                                    'user_id' => $auction->store->user_id,
                                    'title' => 'Your item: ' . $auction->name . ' has been sold via auction.',
                                    'is_read' => 0,
                                    // 'source' =>
                                ]);
                            }
                        } else {
                            // dd('highest bid price is lower than reserve price');
                            if ($listing->relisted == $listing->relist_limit) {

                                // update item listing to sold
                                $listing->update([
                                    'closed' => 1,
                                    'closed_at' => date('Y-m-d H:i:s'),
                                ]);

                                $auction->store->update([
                                    'closed_auctions' => $auction->store->closed_auctions + 1,
                                ]);

                                // send notification to seller that auction is closed
                                $auction->store->user->notify(new ClosingAuctionNotification($auction));

                                // save notification to database
                                Notification::create([
                                    'user_id' => $auction->store->user_id,
                                    'title' => 'Your auction for the item: ' . $auction->name . ' has been closed without selling.',
                                    'is_read' => 0,
                                ]);
                            } else {
                                // get start time
                                $startTime = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
                                // extract only days number value from duration string (e.g. 7 days)
                                $duration = substr($listing->duration, 0, strpos($listing->duration, ' '));
                                // obtain endTime from startTime and duration
                                $endTime = \Carbon\Carbon::parse($startTime)->addDays($duration)->format('Y-m-d H:i:s');

                                // update item listing to buy-it-now type
                                $listing->update([
                                    'item_type' => 'buy-it-now',
                                    'relisted' => $listing->relisted + 1,
                                    'start_time' => $startTime,
                                    'end_time' => $endTime,
                                    'reserved' => false
                                ]);

                                // update item pricing for buy-it-now type
                                $pricing->update([
                                    'buy_it_now_price' => $pricing->reserve_price,
                                ]);

                                $auction->store->update([
                                    'buyitnow_items' => $auction->store->buyitnow_items + 1,
                                    'closed_auctions' => $auction->store->closed_auctions + 1,
                                ]);

                                // send notification to seller that auction is closed
                                $auction->store->user->notify(new ClosingAuctionNotification($auction));

                                // save notification to database
                                Notification::create([
                                    'user_id' => $auction->store->user_id,
                                    'title' => 'Your auction for the item: ' . $auction->name . ' has been closed without selling.',
                                    'is_read' => 0,
                                    // 'source' =>
                                ]);

                                // save notification to database
                                Notification::create([
                                    'user_id' => $auction->store->user_id,
                                    'title' => 'Your item: ' . $auction->name . ' is relisted as buy it now.',
                                    'is_read' => 0,
                                    // 'source' =>
                                ]);
                            }
                        }
                    }
                }
            } else {
                if ($listing->end_time <= date('Y-m-d H:i:s')) {
                    // dd('not reserved');
                    // check for any bids on the product
                    $bids = $auction->bids;
                    if ($bids->count() == 0) {
                        // update item listing to closed
                        $listing->update([
                            'closed' => 1,
                            'closed_at' => date('Y-m-d H:i:s'),
                        ]);

                        $auction->store->update([
                            'closed_auctions' => $auction->store->closed_auctions + 1,
                        ]);

                        // send notification to seller that auction is closed
                        $auction->store->user->notify(new ClosingAuctionNotification($auction));

                        // save notification to database
                        Notification::create([
                            'user_id' => $auction->store->user_id,
                            'title' => 'Your auction for the item: ' . $auction->name . ' has been closed without selling.',
                            'is_read' => 0,
                            // 'source' =>
                        ]);
                    } else {
                        // the bid with the highest price
                        $highest_price = $bids->max('price');
                        $highest_bid = $bids->where('price', $highest_price)->first();
                        // dd($highest_bid);

                        // update item listing to sold
                        $listing->update([
                            'sold' => 1,
                            'closed' => 1,
                            'closed_at' => date('Y-m-d H:i:s'),
                            'highest_bid_id' => $highest_bid->id,
                            'winner_id' => $highest_bid->user_id
                        ]);

                        // declined all other bids
                        \App\Models\Bid::where('id', '!=', $highest_bid->id)->where('product_id', $auction->id)->update([
                            'status' => 'declined',
                        ]);

                        // make winner the winner bid
                        $highest_bid->update([
                            'status' => 'winner',
                        ]);

                        // create the order for the winning bid
                        $price = $highest_price;
                        $quantity = 1;
                        $insurance = 0;
                        $shipping_cost = 0;
                        $total = ($price * $quantity) + $shipping_cost + $insurance;

                        $createdOrder = \App\Models\Order::create([
                            'user_id' => $highest_bid->user_id,
                            'store_id' => $auction->store_id,
                            'postal_insurance' => $insurance,
                            'shipping_cost' => $shipping_cost,
                            'total' => $total,
                            'status' => 'pending',
                            'payment_status' => 'pending',
                            'won_via' => 'auction',
                        ]);
                        if ($createdOrder) {
                            // also create order items
                            \App\Models\OrderItem::create([
                                'order_id' => $createdOrder->id,
                                'product_id' => $auction->id,
                                'quantity' => $quantity,
                                'price' => $price,
                            ]);

                            $auction->store->update([
                                'closed_auctions' => $auction->store->closed_auctions + 1,
                            ]);

                            // send notification to winning user
                            $highest_bid->user->notify(new WinningAuctionNotification($auction));

                            // save notification to database
                            Notification::create([
                                'user_id' => $highest_bid->user_id,
                                'title' => 'You won an auction for the item: ' . $auction->name,
                                'is_read' => 0,
                                // 'source' =>
                            ]);

                            Notification::create([
                                'user_id' => $auction->store->user_id,
                                'title' => 'Your item: ' . $auction->name . ' has been sold via auction.',
                                'is_read' => 0,
                                // 'source' =>
                            ]);
                        }
                    }
                }
            }
        }

        // get buy-it-now items that are not sold and has an end time less than current time
        $buyItNowItems = \App\Models\Product::whereHas('productListing', function ($query) {
            $query->where('item_type', 'buy-it-now')
                ->where('end_time', '<=', date('Y-m-d H:i:s'))
                ->where('relisting', 'Limited')
                ->where('closed', 0);
        })->where('is_sold', 0)
            ->get();

        foreach ($buyItNowItems as $item) {
            $listing = $item->productListing;

            if ($listing->relisted == $listing->relist_limit) {
                // update item listing to closed
                $listing->update([
                    'closed' => 1,
                    'closed_at' => date('Y-m-d H:i:s'),
                ]);

                // save notification to database
                Notification::create([
                    'user_id' => $auction->store->user_id,
                    'title' => 'Your listing for the item: ' . $auction->name . ' has been closed without selling.',
                    'is_read' => 0,
                    // 'source' =>
                ]);
            } else {
                // get start time
                $startTime = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
                // extract only days number value from duration string (e.g. 7 days)
                $duration = substr($listing->duration, 0, strpos($listing->duration, ' '));
                // obtain endTime from startTime and duration
                $endTime = \Carbon\Carbon::parse($startTime)->addDays($duration)->format('Y-m-d H:i:s');

                // update item listing to buy-it-now type
                $listing->update([
                    'relisted' => $listing->relisted + 1,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                ]);
            }
        }
    }
}
