<?php

namespace App\Http\Controllers;

use App\Model\Rm_class;
use Illuminate\Http\Request;
use DB;
use Auth;

class RoomClassController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }

  public function index()
  {
    return view('rooms.class');
  }

  public function store_update()
  {
    $validateData = $this->validate(request(), [
      'name' => 'required',
      'description' => 'required'
    ]);

    if(request()->action == 'add'){
      Rm_class::create([
        'user_id' => Auth::user()->id,
        'name' => request()->name,
        'description' => request()->description,
        'cost' => request()->cost,
      ]);
    }

    if(request()->action == 'edit'){
      $rmclass = Rm_class::findOrFail(request()->cl);
      $rmclass->update([
        'name' => request()->name,
        'description' => request()->description,
        'cost' => request()->cost,
        'updated_at' => date('Y-m-d H:i:s'),
      ]);
    }

    return 'success';
  }

  public function show()
  {
    $ctd = [];
    $class = Rm_class::findOrFail($_POST['id']);
    $cdata['name'] = $class->name;
    $cdata['description'] = $class->description;
    $cdata['cost'] = $class->cost;
    echo json_encode($cdata);
  }

  public function api()
  {
    $class = Rm_class::select('id', 'name', 'description', 'cost', 'created_at');
    return datatables($class)
      ->addColumn('action', function ($class) {
        return '
        <button count="'.$class->id.'" class="btn btn-info btn-sm view mx-1"><i class="fa fa-eye"></i></buttom>
        <button count="'.$class->id.'" class="btn btn-success btn-sm edit mx-1"><i class="fa fa-edit"></i></buttom>
        <button count="'.$class->id.'" class="btn btn-danger delete btn-sm mx-1"><i class="fa fa-trash"></i></buttom>
        ';
    })
    ->make(true);
  }

  public function destroy()
  {
    Rm_class::destroy($_POST['id']);
    return 'success';
  }

  public function getAll()
  {
    echo Rm_class::orderBy('name', 'asc')->get();
  }

}
