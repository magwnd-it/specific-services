<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use DB;

class ProductsController extends Controller
{
    public function index() {

        $products = DB::table('products') ->orderByDesc('id')->get();
        
        return view('admin.products', ['products' => $products]);
    }

    public function AddProducts(Request $request) {

        $validate = $request->validate(
            ['product_name' => ['required', 'string', 'max:70']]);

        $product = new Product();

        $product->product_name = strip_tags($request['product_name']);

        $product->save();
        $request->session()->flash('success', 'Product has been added successfully');
        return redirect()->back();
        
    }

    public function DeleteProduct($id) {

        $delete = DB::table('products')->where('id', '=', $id)->delete();
        
        if($delete) {
            session()->flash('success', 'Product has been Deleted successfully');
            return redirect()->back();
        }

    }

    public function EditProduct($id) {

        $product = Product::find($id);
        return view('admin.edit-product', ['product'=> $product]);

    }

    public function UpdateProduct(Request $request) {
    
        $data= Product::find($request->id);

        if(!$data == null) {

            $validate = $request->validate(
            ['product_name' => ['required', 'string', 'max:70']]);

            $data->product_name=strip_tags($request['product_name']);
            $data->save();
            session()->flash('success', 'Product has been Updated successfully');
            return redirect()->back();

        }else {
            session()->flash('error', 'There is an error on the inputs, please refresh the page');
            return redirect()->back();
        }
    }

}