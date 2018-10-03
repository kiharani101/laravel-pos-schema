<?php

namespace App\Http\Controllers;

use App\Model\Rm_class;
use App\Model\Rm_booking;
use App\Model\Room;
use Illuminate\Http\Request;
use DB;
use Auth;

class RoomController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }

  public function index()
  {
    $classes = Rm_class::orderBy('name', 'asc')->get();
    return view('rooms.room', compact('classes'));
  }

  public function store_update()
  {
    $validateData = $this->validate(request(), [
      'room_number' => 'required',
      'room_class' => 'required'
    ]);

    if(request()->action == 'add'){
      Room::create([
        'user_id' => Auth::user()->id,
        'number' => request()->room_number,
        'rm_class_id' => request()->room_class,
      ]);
    }

    if(request()->action == 'edit'){
      $rmclass = Room::findOrFail(request()->cl);
      $rmclass->update([
        'umber' => request()->room_number,
        'rm_class_id' => request()->room_class,
        'updated_at' => date('Y-m-d H:i:s'),
      ]);
    }

    return 'success';
  }

  public function show()
  {
    $rtd = [];
    $room = Room::findOrFail($_POST['id']);
    $rdata['number'] = $room->number;
    $rdata['class_id'] = $room->rm_class_id;
    $rdata['class_name'] = $room->rm_class->name;
    $rdata['price'] = $room->rm_class->cost;
    echo json_encode($rdata);
  }

  public function api()
  {
    $room = Room::select('*');
    return datatables($room)
    ->addColumn('class', function ($room) {
        return $room->rm_class->name;
    })
    ->addColumn('cost', function ($room) {
        return $room->rm_class->cost;
    })
      ->addColumn('action', function ($room) {
        return '
        <button count="'.$room->id.'" class="btn btn-info btn-sm view mx-1"><i class="fa fa-eye"></i></buttom>
        <button count="'.$room->id.'" class="btn btn-success btn-sm edit mx-1"><i class="fa fa-edit"></i></buttom>
        <button count="'.$room->id.'" class="btn btn-danger delete btn-sm mx-1"><i class="fa fa-trash"></i></buttom>
        ';
    })
    ->make(true);
  }

  public function destroy()
  {
    Room::destroy($_POST['id']);
    return 'success';
  }

  public function getAll()
  {
    echo Room::orderBy('name', 'asc')->get();
  }

  public function allocate()
  {
    $rooms = Room::orderBy('number', 'asc')->get();
    return view('rooms.allocate', compact('rooms'));
  }

  public function complete()
  {
    DB::beginTransaction();

    $rm_id = request()->room_id;
    $price = request()->price;

    for ($i=0; $i < count($rm_id); $i++) { 
      $book = Rm_booking::create([
        'user_id' => Auth::user()->id,
        'room_id' => $rm_id[$i],
        'price' => $price[$i],
        'client_name' => request()->client_name,
        'client_id' => request()->client_id,
        'checkin_time' => date('Y-m-d H:i:s'),
      ]);

      $room = Room::find($rm_id[$i]);
      $update = $room->update([
        'status' => 'Occupied',
      ]);
    }

    if(!$book || !$update){
      DB::rollBack();
      echo "Operation failed!";
    }else{
      DB::commit();
      echo "Operation successful!";
    }
  }

}
