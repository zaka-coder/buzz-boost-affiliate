<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\ProductListing;
use App\Models\ProductPricing;
use App\Models\ProductShipping;
use App\Models\SubSubCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct()
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $store = Auth::user()->store;
        if ($store) {
            if ($store->approved) {
                $products = Product::where('store_id', $store->id)->get();
                return view('seller.product.index', compact('products', 'store'));
            } else {
                return redirect()->route('seller.dashboard');
            }
        } else {
            return redirect()->route('seller.dashboard');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('seller.product.select-type');
    }

    public function createProduct($type)
    {
        $store = Auth::user()->store;
        // dd($store->plan->buyitnow_items);

        if ($type == 'auction' && $store->plan->auctions_items <= $store->auctions_items) {
            return redirect()->back()->with('error', 'You have reached your limit of auction items. Please upgrade your plan.');
        } elseif ($store->plan->buyitnow_items <= $store->buyitnow_items) {
            return redirect()->back()->with('error', 'You have reached your limit of buy it now items. Please upgrade your plan.');
        }

        $categories = Category::where('parent_id', null)->get();
        return view('seller.product.create', compact('type', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:75',
            'description' => 'required',
            'category_id' => 'required',
            'weight' => 'required|numeric',
            'dim_width' => 'required|numeric',
            'dim_length' => 'required|numeric',
            'dim_depth' => 'required|numeric',
            'certified' => 'required',
            'treatment' => 'required',
            // 'thumbnail' => 'required|image',
            'video' => 'nullable|mimes:mp4,webm,mkv,3gp',
            'clarity' => 'required',
            'shapes.*' => 'required',
            'types.*' => 'required',
            'gallery.*' => 'nullable|image',
            'listing_type' => 'required',
            'duration' => 'required',
            'start' => 'required',
            'start_date' => $request->input('start') == 'Later' ? 'required' : '',
            'start_time' => $request->input('start') == 'Later' ? 'required' : '',
            'shipping_type' => 'required',
            'custom_shipping_price' => $request->input('shipping_type') == 'custom' ? 'required|numeric' : '',
            'starting_price' => 'nullable|numeric',
            'reserve_price' => $request->input('reserve') == '1' ? 'required|numeric' : '',
            'buyitnow_price' => $request->input('item_type') != 'auction' ? 'required|numeric' : '',
            'retail_price' => 'nullable|numeric',
            'relisting' => $request->input('item_type') == 'auction' ? 'required' : 'nullable',
        ], [
            'category_id.required' => 'Please select a valid category.',
        ]);

        // Additional validation for custom shipping price
        if ($request->input('shipping_type') == 'custom') {
            $request->validate([
                'custom_shipping_price' => 'numeric',
            ]);
        }

        $itemType = $request->item_type;
        $store = auth()->user()->store;
        // $imageUrl = null;
        $videoUrl = null;
        $productTypes = null;
        $productShapes = null;
        $reserve = $itemType == 'auction' ? $request->reserve : 0;

        // check for thumbnail
        // if ($request->hasFile('thumbnail')) {
        //     $image = $request->file('thumbnail');
        //     $name_gen = uniqid();
        //     $img_ext = strtolower($image->getClientOriginalExtension());
        //     $filename =  $name_gen . '-' . time() . '.' . $img_ext;
        //     $folderPath = 'images/products/';
        //     $image->move($folderPath, $filename); // save image to public folder
        //     $imageUrl = $folderPath .  $filename;
        // }

        // check for video
        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $name_gen = uniqid();
            $vid_ext = strtolower($video->getClientOriginalExtension());
            $filename =  $name_gen . '-' . time() . '.' . $vid_ext;
            $folderPath = 'videos/products/';
            $video->move($folderPath, $filename); // save image to public folder
            $videoUrl = $folderPath .  $filename;
        }

        // extract shapes from request and save it in a string
        $shapes = $request->shape;
        if (is_array($shapes)) {
            $productShapes = implode(',', $shapes);
        }

        // extract types from request and save it in a string
        $types = $request->type;
        if (is_array($types)) {
            $productTypes = implode(',', $types);
        }

        $product = Product::create([
            'store_id' => $store->id,
            'name' => $request->title,
            'description' => $request->description,
            'weight' => $request->weight,
            'dim_width' => $request->dim_width,
            'dim_length' => $request->dim_length,
            'dim_depth' => $request->dim_depth,
            'is_certified' => $request->certified,
            'treatment' => $request->treatment,
            'clarity' => $request->clarity,
            // 'image' => $imageUrl,
            'video' => $videoUrl,
            'category_id' => $request->category_id,
            'shapes' => $productShapes,
            'types' => $productTypes,
        ]);


        if ($product) {
            // for gallery
            if ($request->hasFile('gallery')) {
                $gallery = $request->file('gallery');
                // Loop through images and save them to the database
                foreach ($gallery as $key => $image) {
                    $name_gen = uniqid();
                    $img_ext = strtolower($image->getClientOriginalExtension());
                    $filename =  $name_gen . '-' . time() . '.' . $img_ext;
                    $folderPath = 'images/products/gallery/';
                    $url = $folderPath .  $filename;
                    $image->move($folderPath, $filename); // save image to public folder

                    // create gallery
                    Gallery::create([
                        'product_id' => $product->id,
                        'image' => $url,
                    ]);

                    if ($key == 0) {
                        $product->image = $url;
                        $product->save();
                    }
                }
            }

            // for product listing
            $startTime = Carbon::now()->format('Y-m-d H:i:s');
            $endTime = null;
            if ($request->start == 'Later') {
                $startTime = $request->start_date . ' ' . $request->start_time;
                // convert date to Y-m-d H:i:s
                $startTime = Carbon::parse($startTime)->format('Y-m-d H:i:s');
            }

            if ($request->item_type == 'auction') {
                // extract only days number value from duration string (e.g. 7 days)
                $duration = substr($request->duration, 0, strpos($request->duration, ' '));
                // obtain endTime from startTime and duration
                $endTime = Carbon::parse($startTime)->addDays($duration)->format('Y-m-d H:i:s');
            }

            ProductListing::create([
                'product_id' => $product->id,
                'listing_type' => $request->listing_type,
                'duration' => $request->duration,
                'start' => $request->start,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'item_type' => $itemType,
                'relisting' => $request->relisting ?? 'Unlimited',
                'relist_limit' => $request->relisting == 'Limited' ? $request->relist_limit : null,
                'relisted' => 1,
                'reserved' => $reserve,
            ]);

            // for product pricing
            ProductPricing::create([
                'product_id' => $product->id,
                'buy_it_now_price' => $request->buyitnow_price,
                'retail_price' => $request->retail_price,
                'reserve_price' => $reserve == 0 ? null : $request->reserve_price,
                'starting_price' => $request->starting_price == null ? 0 : $request->starting_price,
            ]);

            // for shipping
            ProductShipping::create([
                'product_id' => $product->id,
                'shipping_type' => $request->shipping_type,
                'custom_shipping_cost' => $request->custom_shipping_price

            ]);

            if ($itemType == 'auction') {
                $store->auctions_items = $store->auctions_items + 1;
            } else {
                $store->buyitnow_items = $store->buyitnow_items + 1;
            }

            return redirect()->route('seller.items.index')->with('success', 'Item created successfully.');
        }
        return redirect()->back()->with('error', 'Something went wrong, please try again.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        return view('seller.product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::where('parent_id', null)->get();
        return view('seller.product.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->input('item_type'));
        $product = Product::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:75',
            'description' => 'required',
            'category_id' => 'required',
            'weight' => 'required|numeric',
            'dim_width' => 'required|numeric',
            'dim_length' => 'required|numeric',
            'dim_depth' => 'required|numeric',
            'certified' => 'required',
            'treatment' => 'required',
            'video' => 'nullable|mimes:mp4,webm,mkv,3gp',
            'clarity' => 'required',
            'shapes.*' => 'required',
            'types.*' => 'required',
            'gallery.*' => 'nullable|image',
            'listing_type' => 'required',
            'duration' => 'required',
            'start' => 'required',
            'start_date' => $request->input('start') == 'Later' ? 'required' : '',
            'start_time' => $request->input('start') == 'Later' ? 'required' : '',
            'shipping_type' => 'required',
            'custom_shipping_price' => $request->input('shipping_type') == 'custom' ? 'required|numeric' : '',
            'starting_price' => 'nullable|numeric',
            'reserve_price' => 'nullable|numeric',
            'buyitnow_price' => $request->input('item_type') != 'auction' ? 'required|numeric' : '',
            'retail_price' => 'nullable|numeric',
            'relisting' => 'nullable',
        ], [
            'category_id.required' => 'Please select a valid category.',
        ]);

        // Additional validation for custom shipping price
        // if ($request->input('shipping_type') == 'custom') {
        //     $request->validate([
        //         'custom_shipping_price' => 'numeric',
        //     ]);
        // }

        $imageUrl = $product->image;
        $itemType = $request->item_type;
        $videoUrl = null;
        $productTypes = null;
        $productShapes = null;
        $reserve = $itemType == 'auction' ? $request->reserve : 0;

        // check for thumbnail
        // if ($request->hasFile('thumbnail')) {
        //     $request->validate([
        //         'thumbnail' => 'image',
        //     ]);
        //     // delete old image
        //     if ($request->file('thumbnail') && $product->image) {
        //         $oldImagePath = public_path($product->image);
        //         if (file_exists($oldImagePath)) {
        //             unlink($oldImagePath);
        //         }
        //     }

        //     $image = $request->file('thumbnail');
        //     $name_gen = uniqid();
        //     $img_ext = strtolower($image->getClientOriginalExtension());
        //     $filename =  $name_gen . '-' . time() . '.' . $img_ext;
        //     $folderPath = 'images/products/';
        //     $image->move($folderPath, $filename); // save image to public folder
        //     $imageUrl = $folderPath .  $filename;
        // }

        // check for video
        if ($request->hasFile('video')) {
            // delete old video
            if ($product->video) {
                $oldVideoPath = public_path($product->video);
                if (file_exists($oldVideoPath)) {
                    unlink($oldVideoPath);
                }
            }
            $video = $request->file('video');
            $name_gen = uniqid();
            $vid_ext = strtolower($video->getClientOriginalExtension());
            $filename =  $name_gen . '-' . time() . '.' . $vid_ext;
            $folderPath = 'videos/products/';
            $video->move($folderPath, $filename); // save image to public folder
            $videoUrl = $folderPath .  $filename;
        }

        // extract shapes from request and save it in a string
        $shapes = $request->shape;
        if (is_array($shapes)) {
            $productShapes = implode(',', $shapes);
        }

        // extract types from request and save it in a string
        $types = $request->type;
        if (is_array($types)) {
            $productTypes = implode(',', $types);
        }

        $product->update([
            'name' => $request->title,
            'description' => $request->description,
            'weight' => $request->weight,
            'dim_width' => $request->dim_width,
            'dim_length' => $request->dim_length,
            'dim_depth' => $request->dim_depth,
            'is_certified' => $request->certified,
            'treatment' => $request->treatment,
            'clarity' => $request->clarity,
            'image' => $imageUrl,
            'video' => $videoUrl,
            'category_id' => $request->category_id,
            'shapes' => $productShapes,
            'types' => $productTypes,
        ]);


        if ($product) {
            // for gallery
            if ($request->hasFile('gallery')) {
                $gallery = $request->file('gallery');
                // Loop through images and save them to the database
                foreach ($gallery as $key => $image) {
                    $name_gen = uniqid();
                    $img_ext = strtolower($image->getClientOriginalExtension());
                    $filename =  $name_gen . '-' . time() . '.' . $img_ext;
                    $folderPath = 'images/products/gallery/';
                    $url = $folderPath .  $filename;
                    $image->move($folderPath, $filename); // save image to public folder

                    // create gallery
                    Gallery::create([
                        'product_id' => $product->id,
                        'image' => $url,
                    ]);
                }
            }

            // for product listing
            if ($request->start == 'Later') {
                $startTime = $request->start_date . ' ' . $request->start_time;
                // convert date to Y-m-d H:i:s
                $startTime = Carbon::parse($startTime)->format('Y-m-d H:i:s');
                $endTime = $product->productListing->end_time;
                if ($itemType == 'auction') {
                    // extract only days number value from duration string (e.g. 7 days)
                    $duration = substr($request->duration, 0, strpos($request->duration, ' '));
                    // obtain endTime from startTime and duration
                    $endTime = Carbon::parse($startTime)->addDays($duration)->format('Y-m-d H:i:s');
                }

                ProductListing::where('product_id', $product->id)->update([
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'duration' => $request->duration,
                ]);
            } elseif ($request->start == 'Now' && $request->duration != $product->productListing->duration) {
                // extract only days number value from duration string (e.g. 7 days)
                $duration = substr($request->duration, 0, strpos($request->duration, ' '));
                // obtain endTime from startTime and duration
                $endTime = Carbon::parse($product->productListing->start_time)->addDays($duration)->format('Y-m-d H:i:s');

                ProductListing::where('product_id', $product->id)->update([
                    'end_time' => $endTime,
                    'duration' => $request->duration,
                ]);
            }


            ProductListing::where('product_id', $product->id)->update([
                'listing_type' => $request->listing_type,
                'duration' => $request->duration,
                'start' => $request->start,
                'item_type' => $itemType,
                'relisting' => $request->relisting,
                'relist_limit' => $request->relisting == 'Limited' ? $request->relist_limit : null,
                'reserved' => $reserve,
            ]);

            // for product pricing
            ProductPricing::where('product_id', $product->id)->update([
                'buy_it_now_price' => $request->buyitnow_price,
                'retail_price' => $request->retail_price,
                'reserve_price' => $reserve == 0 ? null : $request->reserve_price,
                'starting_price' => $request->starting_price == null ? 0 : $request->starting_price,
            ]);

            // for shipping
            ProductShipping::where('product_id', $product->id)->update([
                'shipping_type' => $request->shipping_type,
                'custom_shipping_cost' => $request->custom_shipping_price

            ]);

            return redirect()->route('seller.items.index')->with('success', 'Item updated successfully.');
        }
        return redirect()->back()->with('error', 'Something went wrong, please try again.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('seller.items.index')->with('success', 'Item deleted successfully.');
    }
}
