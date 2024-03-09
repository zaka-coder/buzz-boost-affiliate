<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Audit;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index()
    {
        $audits = Audit::all();
        return view('admin.audit.index', compact('audits'));
    }

    public function response($id)
    {
        $audit = Audit::findOrFail($id);
        return view('admin.audit.response', compact('audit'));
    }

    public function storeResponse(Request $request, $id)
    {
        $this->validate($request, [
            'comment' => 'required',
            'product_desc' => 'required',
            'product_image' => 'required',
        ]);

        $audit = Audit::findOrFail($id);
        $certImageUrl = $audit->cert_image;

        if ($request->hasFile('cert_image')) {
            $image = $request->file('cert_image');
            $name_gen = uniqid();
            $img_ext = strtolower($image->getClientOriginalExtension());
            $filename =  $name_gen . '-' . time() . '.' . $img_ext;
            $folderPath = 'images/products/certificate/';
            $image->move($folderPath, $filename); // save image to public folder
            $certImageUrl = $folderPath .  $filename;
        }

        $audit->comment = $request->comment;
        $audit->product_image = $request->product_image;
        $audit->product_desc = $request->product_desc;
        $audit->cert_image = $certImageUrl;
        $audit->status = $request->status;
        $audit->audit_date = now();
        $audit->save();
        return redirect(route('admin.audits.index'))->with('success', 'Audit Completed Successfully');
    }

    public function showUser($id)
    {
        $audit = Audit::findOrFail($id);
        $audit->load('user'); // eager loading the user relationship
        $user = $audit->user;

        return view('admin.audit.user-info', compact('audit', 'user'));
    }

    public function showProduct($id)
    {
        $audit = Audit::findOrFail($id);
        $audit->load('product'); // eager loading the product relationship
        $product = $audit->product;

        return view('admin.audit.product-info', compact('audit', 'product'));
    }

    public function filterByStatus(Request $request)
    {
        $status = $request->status;
        $audits = Audit::orderBy('id', 'desc')->where('status', $status)->get();
        // dd($audits);
        return view('admin.audit.index', compact('audits', 'status'));
    }
}
