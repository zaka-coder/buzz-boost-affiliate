<?php

namespace App\Traits;

trait RatingTrait {

    // calculate rating percentage
    public function ratingPercentage($feedbacks)
    {
        // extract feedbacks ratings, by taking negative as 1, neutral as 3 and positive as 5
        $ratings = $feedbacks->map(function ($feedback) {
            return $feedback->nature == 'negative' ? 1 : ($feedback->nature == 'neutral' ? 3 : 5);
        })->toArray();
        // taking average of ratings
        $averageRatings = array_sum($ratings) / count($ratings);
        // convert to percentage
        $ratingPercentage = $averageRatings * 100/5;
        // return percentage
        return $ratingPercentage;
    }

    // calculate average rating
    public function ratingAverage($feedbacks)
    {
        // extract feedbacks ratings, by taking negative as 1, neutral as 3 and positive as 5
        $ratings = $feedbacks->map(function ($feedback) {
            return $feedback->nature == 'negative' ? 1 : ($feedback->nature == 'neutral' ? 3 : 5);
        })->toArray();
        // taking average of ratings
        $averageRatings = array_sum($ratings) / count($ratings);
        // return average
        return $averageRatings;
    }
}
