<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait GenericTrait
{
    public function humanReadableDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) =>  Carbon::parse($this->created_at)->diffForHumans(),
        );
    }

    public function uploadMedia($request)
    {
        $name = time() . '_' . $request->getClientOriginalName();
        $filePath = $request->storeAs('uploads', $name, 'public');
        $photo = 'storage/' . $filePath;
        return $photo;
    }
}
