<?php

namespace App\Http\Controllers;

use App\Model\Product;
use App\Model\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }

  public function index()
  {
    $categories = Category::orderBy('name', 'asc')->get();
    return view('pages.product', compact('categories'));
  }

  public function store_update()
  {
    $this->validate(request(), [
      'name' => 'required',
      'price' => 'required',
      'qty' => 'required',
      'category' => 'required',
      'about' => 'required'
    ]);

    if(request()->action == 'add'){
      Product::create([
        'name' => request()->name,
        'price' => request()->price,
        'qty' => request()->qty,
        'category_id' => request()->category,
        'about' => request()->about,
        'user_id' => Auth::user()->id,
      ]);
    }

    if(request()->action == 'edit'){
      $product = Product::findOrFail(request()->pro);
      $product->update([
        'name' => request()->name,
        'price' => request()->price,
        'qty' => request()->qty,
        'category_id' => request()->category,
        'about' => request()->about,
        'updated_at' => date('Y-m-d H:i:s'),
      ]);
    }

    return 'success';
  }

  public function show(){
   // $id = $_POST['task'];
    $pd = [];
    $product = Product::where('id', $_POST['id'])->first();
    $pd['name'] = $product->name;
    $pd['price'] = $product->price;
    $pd['qty'] = $product->qty;
    $pd['owner'] = $product->user->name;
    $pd['category'] = $product->category->name;
    $pd['category_id'] = $product->category->id;
    $pd['about'] = $product->about;
    echo json_encode($pd);
  }

  public function destroy()
  {
    Product::destroy($_POST['id']);
    return 'success';
  }

  public function api()
  {
    $product = Product::select('id', 'name', 'qty', 'price');
    return datatables($product)
      ->addColumn('action', function ($product) {
        return '
        <button count="'.$product->id.'" class="btn btn-info btn-sm view mx-1"><i class="fa fa-eye"></i></buttom>
        <button count="'.$product->id.'" class="btn btn-success btn-sm edit mx-1"><i class="fa fa-edit"></i></buttom>
        <button count="'.$product->id.'" class="btn btn-danger btn-sm delete mx-1"><i class="fa fa-trash"></i></buttom>
        ';
    })
    ->make(true);
  }

  public function getAll(){
    $products = Product::orderBy('name', 'asc')->get();
    echo $products;
  }

}
