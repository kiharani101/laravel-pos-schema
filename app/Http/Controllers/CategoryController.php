<?php

namespace App\Http\Controllers;

use App\Model\Category;
use App\User;
use Illuminate\Http\Request;
use Auth;

class CategoryController extends Controller
{
    
  public function __construct()
  {
      $this->middleware('auth');
  }

  public function index()
  {
    $categories = Category::where('parent', 0)->get();
    return view('pages.category', compact('categories'));
  }

  public function store_update()
  {
    $validateData = $this->validate(request(), [
      'name' => 'required|max: 75|min:2',
      'parent' => 'required'
    ]);

    if(request()->action == 'add'){
      Category::create([
        'name' => request()->name,
        'parent' => request()->parent,
        'user_id' => Auth::user()->id,
      ]);
    }

    if(request()->action == 'edit'){
      $category = Category::findOrFail(request()->cat);
      $category->update([
        'name' => request()->name,
        'parent' => request()->parent,
        'updated_at' => date('Y-m-d H:i:s'),
      ]);
    }

    return 'success';
  }

  public function show(){
   // $id = $_POST['task'];
    $ctd = [];
    $category = Category::findOrFail($_POST['id']);
    $ctd['name'] = $category->name;
    $ctd['parent_id'] = $category->parent;
    $ctd['parent_name'] = $this->parent($category->parent);
    echo json_encode($ctd);
  }

  public function destroy()
  {
    Category::destroy($_POST['id']);
    return 'success';
  }

  private function parent($pr)
  {
    if($pr == 0){
      return 'Root Category';
    }else{
      $cat = Category::findOrFail($pr);
      return $cat->name;
    }
  }

  public function api()
  {
    $category = Category::select('id', 'name', 'parent', 'created_at');
    return datatables($category)
      ->addColumn('parent', function ($category) {
        return $this->parent($category->parent);
    })
      ->addColumn('action', function ($category) {
        return '
        <button count="'.$category->id.'" class="btn btn-info btn-sm view mx-1"><i class="fa fa-eye"></i></buttom>
        <button count="'.$category->id.'" class="btn btn-success btn-sm edit mx-1"><i class="fa fa-edit"></i></buttom>
        <button count="'.$category->id.'" class="btn btn-danger delete btn-sm mx-1"><i class="fa fa-trash"></i></buttom>
        ';
    })
    ->make(true);
  }

  public function getAll(){
    $categories = Category::orderBy('name', 'asc')->get();
    echo $categories;
  }
}
