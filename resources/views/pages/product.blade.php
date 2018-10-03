@extends('layouts.master')

@section('content')

<main class="app-content">
  <div class="card">
    <div class="card-header h3">
      Products
      <button type="button" class="btn btn-success pull-right" id="add-btn"><i class="fa fa-plus-square mr-1"></i>Add</button>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <div class="table-responsive">
          <table class="table table-sm table-bordered" id="table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</main>
@endsection

@push('css')
  <link rel="stylesheet" type="text/css" href="{{ asset('DataTables/datatables.min.css') }}"/>
@endpush
 
@push('scripts')
  <script type="text/javascript" src="{{ asset('DataTables/datatables.min.js') }}"></script>

  <script>
    $(document).ready( function(){

      var dt = $('#table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "{{ route('product.api') }}",
        "columns": [
          { "data": "name"},
          { "data": "qty"},
          { "data": "price"},
          { "data": "action", orderable: false, searchable: false}
        ]
      });

      $('#add-form').parsley();

      $('#add-btn').click(function(){
        $('#name').val('');
        $('#pro').val('');
        $('#subBtn').text('Save to Database');
        $('#addModal .modal-title').text('Add Product');
        $('#add-form')[0].reset();
        $('#action').val('add');
        $('#default_cat').val('');
        $('#default_cat').text('-- select category --');
        $('#addModal').modal('show');
      });

      $('#add-form').submit(function(e){
        e.preventDefault();
        // ajax 
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: $(this).attr('action'),
          method: 'POST',
          data: new FormData(this),
          contentType: false,
          cache: false,
          processData: false,
          success: function(res){
            $('#addModal').modal('hide');
            $('#add-form')[0].reset();
            dt.ajax.reload();
          }
        });
      });

      $(document).on('click', '.edit', function(){
        var id = $(this).attr('count');
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: '{{ route('product.single') }}',
          method: 'POST',
          data: { id:id },
          dataType: 'json',
          success: function(res){
            $('#name').val(res.name);
            $('#price').val(res.price);
            $('#qty').val(res.qty);
            $('#about').val(res.about);
            $('#default_cat').val(res.category_id);
            $('#default_cat').text(res.category);
            $('#pro').val(id);
            $('#subBtn').text('Save Changes');
            $('#addModal .modal-title').text('Edit Product');
            $('#action').val('edit');
            $('#addModal').modal('show');
          }
        });
      });

      $(document).on('click', '.view', function(){
        var id = $(this).attr('count');
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: '{{ route('product.single') }}',
          method: 'POST',
          data: { id:id },
          dataType: 'json',
          success: function(res){
            $('#dname').html(res.name);
            $('#downer').html(res.owner);
            $('#dcategory').html(res.category);
            $('#dabout').html(res.about);
            $('#viewModal').modal('show');
          }
        });
      });

      $(document).on('click', '.delete', function(e){
      var id = $(this).attr('count');

      if(confirm('Are you sure you want to delete this Product?')){
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: '{{ route('product.delete') }}',
          method: 'POST',
          data: { id:id },
          success: function(res){
            dt.ajax.reload();
          }
        });
      }else{
        return false;
      }

    });

    });
  </script>

  <div class="modal fade" tabindex="-1" role="dialog" id="addModal">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <p class="modal-title h5">Add Product</p>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" action="{{ route('product.action') }}" id="add-form">
          @csrf
          <div class="modal-body">
            <div class="form-row">
              <div class="form-group col-sm-6">
                <label for="name">Product name</label>
                <input type="text" id="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" placeholder="Product name..." required data-parsley-trigger="change">
              </div>

              <div class="form-group col-sm-6">
                <label for="price">Product price</label>
                <input type="number" min="0.01" step="0.01" id="price" class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}" name="price" value="{{ old('price') }}" placeholder="Product price..." required data-parsley-trigger="change" data-parsley-pattern="^[0-9.]+$">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-sm-6">
                <label for="name">Quantity</label>
                <input type="number" min="1" id="qty" class="form-control{{ $errors->has('qty') ? ' is-invalid' : '' }}" name="qty" value="{{ old('qty') }}" placeholder="Product quantity..." required data-parsley-trigger="change" data-parsley-pattern="^[0-9]+$">
              </div>
              <div class="form-group col-sm-6">
                <label for="name">Category</label>
                <select id="category" class="form-control{{ $errors->has('category') ? ' is-invalid' : '' }}" name="category" value="{{ old('category') }}" required data-parsley-trigger="change" data-parsley-pattern="^[0-9]+$">
                  <option value="" id="default_cat">-- select category --</option>
                  @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-group">
              <textarea id="about" class="form-control{{ $errors->has('about') ? ' is-invalid' : '' }}" name="about" value="{{ old('about') }}" placeholder="Short description about the product..." rows="8" required data-parsley-trigger="change"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <div class="form-group d-flex">
              <button type="button" class="btn btn-danger mx-1" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-info mx-1" id="subBtn">Save to Database</button>
              <input type="hidden" name="action" id="action" value="add">
              <input type="hidden" name="pro" id="pro" value="">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" tabindex="-1" role="dialog" id="viewModal">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <p class="modal-title h5">Product Details</p>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
          <div class="modal-body">
            <p>Name: <b id="dname"></b></p>
            <p>Owner: <b id="downer"></b></p>
            <p>Category: <b id="dcategory"></b></p>
            <p class="h4">About the Product</p>
            <p id="dabout"></p>
          </div>
          <div class="modal-footer">
            <div class="form-group d-flex">
              <button type="button" class="btn btn-danger mx-1" data-dismiss="modal">Close</button>
            </div>
          </div>
      </div>
    </div>
  </div>
@endpush

