<?php

namespace App\Http\Controllers;

use App\Model\Product;
use App\Model\Txn;
use App\Model\Sale;
use Illuminate\Http\Request;
use DB;
// use Illuminate\Support\Facades\Auth;
use Auth;

class SaleController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }

  public function index()
  {
    $products = Product::orderBy('name', 'asc')->get();
    $op = '';
    $no = 1;
  	$op .= '
  	<tr>
      <td><b class="number">1</b></td>
      <td>
        <select name="pid[]" class="form-control pid" required>
          <option value="">-- Search products --</option>';
    foreach ($products as $product){
      $op .= '<option value="'.$product->id.'">'.$product->name.'</option>';
    }
    $op .= '
	      </select>
	    </td>
	    <td><input name="tqty" class="form-control tqty" readonly></td>
	    <td><input type="number" min="1" name="qty[]" class="form-control qty" required></td>
	    <td><input type="text" name="price[]" class="form-control price" readonly></td>
	    <td class="d-none"><input type="hidden" name="pname[]" class="pname"></td>
	    <td><input type="text" class="form-control amt" readonly></td>
	  </tr>';
    return view('pages.make_sales', compact('op'));
  }

  public function complete()
  {
  	//DB::beginTransaction();
  	//DB::rollBack();
  	//DB::commit();
  	DB::beginTransaction();
  	$txn = Txn::create([
        'user_id' => Auth::user()->id,
        'total_cost' => request()->net_total,
        'mdpayment' => request()->mop,
      ]);

  	$txn_id = $txn->id;
  	$product_id = request()->pid;
  	$price = request()->price;
  	$qty = request()->qty;

  	for ($i=0; $i < count($product_id); $i++) { 
  		$sale = Sale::create([
  			'txn_id' => $txn_id,
  			'product_id' => $product_id[$i],
  			'price' => $price[$i],
  			'qty' => $qty[$i]
  		]);

  		$product = Product::find($product_id[$i]);
  		$update = $product->update([
  			'qty' => $product->qty - $qty[$i]
  		]);
  	}

  	if(!$txn || !$sale || !$update){
  		DB::rollBack();
  		echo "Operation failed!";
  	}else{
  		DB::commit();
  		echo "Operation successful!";
  	}
  }

}
