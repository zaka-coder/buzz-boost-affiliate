<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Notifications\DeclinedOfferNotification;
use Illuminate\Console\Command;

class CheckExpiredOffers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'offers:check-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for expired offers and decline them';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $offers = \App\Models\Offer::where('created_at', '<=', now()->subDays(2))->get();

        if ($offers->count() > 0) {
            foreach ($offers as $offer) {
                $offer->status = 'declined';
                $offer->save();

                // send email notification to buyer
                $offer->user->notify(new DeclinedOfferNotification($offer));

                // save notification to database
                Notification::create([
                    'user_id' => $offer->user_id,
                    'title' => 'Your offer has been declined for the item: ' . $offer->product->name,
                    'is_read' => 0,
                ]);

                $this->info('Offer #' . $offer->id . ' has been declined.');
            }
            // $this->info('Expired offers have been declined.');
        } else {
            $this->info('No expired offers found.');
        }
    }
}
