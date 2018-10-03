@extends('layouts.master')

@section('content')

<main class="app-content">
  <div class="card">
    <div class="card-header h3">
    Rooms Management
    <button type="button" class="btn btn-success pull-right ml-1" id="class-btn"><i class="fa fa-plus-square mr-1"></i>Add Class</button>
    <button type="button" class="btn btn-success pull-right" id="room-btn"><i class="fa fa-plus-square mr-1"></i>Add Room</button>
  </div>
    <div class="card-body">
      <form method="post" id="order-form" action="{{ route('rooms.allocate.complete') }}">
        @csrf

        <div class="form-group row">
          <label class="col-sm-3 col-form-label" for="client_name" align="right">Customer Name</label>
          <div class="col-sm-6">
            <input type="text" name="client_name" id="client_name" class="form-control form-sm" placeholder="Enter customer name">
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-3 col-form-label" for="client_id" align="right">ID/Passport No.</label>
          <div class="col-sm-6">
            <input type="text" name="client_id" id="client_id" class="form-control form-sm" placeholder="ID/Passport Number">
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <table align="center" width="100%" class="table table-sm">
              <thead>
                <tr>
                  <th>#</th>
                  <th class="text-center">Room Number</th>
                  <th class="text-center">Class</th>
                  <th class="text-center">Cost (KES)</th>
                </tr>
              </thead>
              <tbody id="room-data">

              </tbody>
            </table>

            <center>
              <button id="add" type="button" class="btn btn-success mx-1 px-3"><i class="fa fa-plus mr-1"></i>Add</button>
              <button id="remove" type="button" class="btn btn-danger mx-1 px-3"><i class="fa fa-times mr-1"></i>Remove</button>
            </center>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 col-form-label" for="sub_total" align="right"><b>Sub total</b></label>
          <div class="col-sm-6">
            <input type="text" name="sub_total" id="sub_total" class="form-control form-lg" value="0" readonly>
          </div>
        </div>

         <div class="form-group row">
          <label class="col-sm-3 col-form-label" for="discount" align="right"><b>Discount</b></label>
          <div class="col-sm-6">
            <input type="number" min="0" name="discount" id="discount" class="form-control form-lg" value="0">
          </div>
        </div>

         <div class="form-group row">
          <label class="col-sm-3 col-form-label" for="net_total" align="right"><b>Net Total</b></label>
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
          <label class="col-sm-3 col-form-label" for="balance" align="right"><b>Balance</b></label>
          <div class="col-sm-6">
            <input type="text" name="balance" id="balance" class="form-control form-lg" value="0" readonly>
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
          <button type="button" id="sbtn" class="btn btn-warning"><i class="fa fa-eye"></i>View Summary</i></button>
          <button id="complete" type="submit" class="btn btn-primary mx-1 px-3"><i class="fa fa-check mr-1"></i>Complete booking</button>
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
      $('#room-data').children('tr:last').remove();
      calculateCost();
    });

    function addRow(){
      
      $('#room-data').append(`<tr>
          <td><b class="number">1</b></td>
          <td>
            <select name="room_id[]" class="form-control room_id" required>
              <option value="">-- Search rooms --</option>
              @forelse ($rooms as $room)
                <option value="{{ $room->id }}">{{ $room->number }}</option>
              @empty
                <option value=""><span class="text-danger">No available rooms</span></option>
              @endforelse
            </select>
          </td>
          <td><input type="text" name="class_name[]" class="form-control class_name" readonly></td>
          <td><input type="text" name="price[]" class="form-control price" readonly></td>
        </tr>`);
      var no = 0;
      $('.number').each(function(){
        $(this).html(++no);
      });
    }

    $(document).on('change', '.room_id', function(){
      var room_id = $(this).val();
      var tr = $(this).parent().parent();
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '{{ route('rooms.single') }}',
        method: 'POST',
        dataType: 'json',
        data: { id:room_id },
        beforeSend: function(){
          $('#add').html('Please wait...');
          $('#add').prop("disabled",true);
        },
        success: function(res){
          tr.find('.price').val(res.price);
          tr.find('.class_name').val(res.class_name);
          tr.find('.room_number').val(res.room_number);
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

    function calculateCost(dis = 0, pd = 0){
      var st = 0;
      var nt = 0;
      var balance = 0;
      var discount = dis;
      var paid = pd;
      $('.price').each(function(){
        st = (st - 0) + ($(this).val() - 0);
      });
      nt = st - discount;
      balance = paid - nt;
      $('#sub_total').val(st);
      $('#discount').val(discount);
      $('#net_total').val(nt);
      $('#paid').val(paid);
      $('#balance').val(balance);

      $('#tdue').html(nt);
      $('#tpaid').html(paid);
      $('#tbal').html(balance);
    }

    $('#sbtn').click(function(){
      $('#summary').modal('show');
    });

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
        url: $(this).attr('action'),
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
          $('#room-data').children('tr').remove();
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

<div class="modal fade" tabindex="-1" role="dialog" id="summary">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <p class="modal-title h5">Summary</p>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
          <div class="modal-body">
            <p class="h1">Sub-total: <span id="tdue" class="text-primary"></span></p>
            <p class="h1">Paid: <span id="tpaid" class="text-success"></span></p>
            <p class="h1">Balance: <span id="tbal" class="text-danger"></span></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger mx-1" data-dismiss="modal">Close</button>
          </div>
      </div>
    </div>
  </div>

@endpush