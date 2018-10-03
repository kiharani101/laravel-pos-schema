@extends('layouts.master')

@section('content')

<main class="app-content">
  <div class="card">
    <p class="card-header h3">Make Sales</p>
    <div class="card-body">
      <form method="post" id="order-form">
        @csrf

        <div class="form-group row">
          <label class="col-sm-3 col-form-label" for="date" align="right">Date</label>
          <div class="col-sm-6">
            <input type="text" name="date" id="date" readonly class="form-control form-sm" value="{{ date('Y-d-m H:i:s') }}">
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-3 col-form-label" for="cname" align="right">Customer Name</label>
          <div class="col-sm-6">
            <input type="text" name="cname" id="cname" class="form-control form-sm" placeholder="Enter customer name">
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <table align="center" width="100%" class="table table-sm">
              <thead>
                <tr>
                  <th>#</th>
                  <th class="text-center">Item Name</th>
                  <th class="text-center">Total Qty</th>
                  <th class="text-center">Qty</th>
                  <th class="text-center">Price (KES)</th>
                  <th>Total (KES)</th>
                </tr>
              </thead>
              <tbody id="sales-data">

              </tbody>
            </table>

            <center>
              <button id="add" type="button" class="btn btn-success mx-1 px-3"><i class="fa fa-plus mr-1"></i>Add</button>
              <button id="remove" type="button" class="btn btn-danger mx-1 px-3"><i class="fa fa-times mr-1"></i>Remove</button>
            </center>
          </div>
        </div>
        <div class="form-group row mt-4">
          <label class="col-sm-3 col-form-label" for="sub_total" align="right"><b>Total cost</b></label>
          <div class="col-sm-6">
            <input type="text" name="sub_total" id="sub_total" class="form-control form-lg" value="0" readonly>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-3 col-form-label" for="vat" align="right"><b>VAT (16%)</b></label>
          <div class="col-sm-6">
            <input type="text" name="vat" id="vat" class="form-control form-lg" value="0" readonly>
          </div>
        </div>

         <div class="form-group row">
          <label class="col-sm-3 col-form-label" for="discount" align="right"><b>Discount</b></label>
          <div class="col-sm-6">
            <input type="number" min="0" name="discount" id="discount" class="form-control form-lg" value="0">
          </div>
        </div>

         <div class="form-group row">
          <label class="col-sm-3 col-form-label" for="net_total" align="right"><b>Sub total</b></label>
          <div class="col-sm-6">
            <input type="text" name="net_total" id="net_total" class="form-control form-lg" value="0" readonly>
          </div>
        </div>

         <div class="form-group row">
          <label class="col-sm-3 col-form-label" for="paid" align="right"><b>Paid</b></label>
          <div class="col-sm-6">
            <input type="number" min="0" name="paid" id="paid" class="form-control form-lg" value="0">
          </div>
        </div>

         <div class="form-group row">
          <label class="col-sm-3 col-form-label" for="due" align="right"><b>Due</b></label>
          <div class="col-sm-6">
            <input type="text" name="due" id="due" class="form-control form-lg" value="0" readonly>
          </div>
        </div>

         <div class="form-group row">
          <label class="col-sm-3 col-form-label" for="mop" align="right"><b>Mode of Payment</b></label>
          <div class="col-sm-6">
            <select name="mop" id="mop" class="form-control form-lg">
              <option value="Cash">Cash</option>
              <option value="Mpesa">Mpesa</option>
              <option value="Others">Others</option>
            </select>
          </div>
        </div>

        <center>
          <button id="complete" type="submit" class="btn btn-primary mx-1 px-3"><i class="fa fa-check mr-1"></i>Complete sales</button>
        </center>
      </form>
    </div>
  </div>
</main>
@endsection

@push('css')
  {{--  --}}
@endpush
 
@push('scripts')
<script>
  $(document).ready(function(){
    addRow();

    $('#add').click(function(){
      addRow();
    });

    $('#remove').click(function(){
      $('#sales-data').children('tr:last').remove();
      calculateCost();
    });

    function addRow(){
      var res = `{!! $op !!}`;
      $('#sales-data').append(res);
      var no = 0;
      $('.number').each(function(){
        $(this).html(++no);
      });
    }

    $(document).on('change', '.pid', function(){
      var pid = $(this).val();
      var tr = $(this).parent().parent();
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '{{ route('product.single') }}',
        method: 'POST',
        dataType: 'json',
        data: { id:pid },
        beforeSend: function(){
          $('#add').html('Please wait...');
          $('#add').prop("disabled",true);
        },
        success: function(res){
          tr.find('.tqty').val(res.qty);
          tr.find('.qty').val(1);
          tr.find('.price').val(res.price);
          tr.find('.amt').val(tr.find('.price').val() * tr.find('.qty').val());
          tr.find('.pname').val(res.name);
          calculateCost();
        },
        error: function(xhr){
          alert("Error occured: "+xhr.message);
        },
        complete: function(){
          $('#add').html('<i class="fa fa-plus mr-1"></i>Add');
          $('#add').prop("disabled",false);
        }

      });
    });

    $(document).on('keyup', '.qty', function(){
      var qty = $(this);
      var tr = $(this).parent().parent();
      if((tr.find('.qty').val() - 0) > (tr.find('.tqty').val() - 0)){
        alert('Sorry, but there is insufficient stock! Please reduce the sale quantity.');
        tr.find('.qty').val(1);
        tr.find('.amt').val(tr.find('.price').val() * tr.find('.qty').val());
      }
      tr.find('.amt').val(tr.find('.price').val() * tr.find('.qty').val());
      calculateCost();
    });

    function calculateCost(dis = 0, pd = 0){
      var st = 0;
      var vat = 0;
      var nt = 0;
      var due = 0;
      var discount = dis;
      var paid = pd;
      $('.amt').each(function(){
        st = (st - 0) + ($(this).val() - 0);
      });
      vat = 0.16 * st;
      nt = st - discount;
      due = nt - paid;
      $('#sub_total').val(st*0.84 - 0);
      $('#vat').val(vat);
      $('#discount').val(discount);
      $('#net_total').val(nt);
      $('#paid').val(paid);
      $('#due').val(due);
    }

    $('#discount').keyup(function(){
      var discount = $(this).val();
      calculateCost(discount);
    });

    $('#paid').keyup(function(){
      var paid = $(this).val();
      var discount = $('#discount').val();
      calculateCost(discount, paid);
    });

    $('#order-form').submit(function(e){
      var fdata = new FormData(this);
      e.preventDefault();
      $.ajax({
        url: '{{ route('sales.complete') }}',
        method: 'POST',
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function(){
          $('#complete').html('Processing...');
          $('#complete').prop("disabled",true);
        },
        success: function(res){
          alert(res);
          $('#order-form')[0].reset();
          $('#sales-data').children('tr').remove();
          addRow();
          calculateCost();
          console.log(fdata);
        },
        error: function(xhr){
          alert("Error occured: "+xhr.message);
        },
        complete: function(){
          $('#complete').html('<i class="fa fa-check mr-1"></i>Complete sales');
          $('#complete').prop("disabled",false);
        }
      });
    });

  });
</script>
@endpush