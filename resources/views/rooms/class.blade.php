@extends('layouts.master')

@section('content')

<main class="app-content">
  <div class="card">
    <div class="card-header h3">
      Rooms Classes
      <button type="button" class="btn btn-success pull-right" id="add-btn"><i class="fa fa-plus-square mr-1"></i>Add</button>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-sm table-bordered" id="table">
          <thead>
            <tr>
              <th>Class name</th>
              <th>Cost (KES)</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
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
        "ajax": "{{ route('rooms.class.api') }}",
        "columns": [
          { "data": "name"},
          { "data": "cost"},
          { "data": "action", orderable: false, searchable: false}
        ]
      });

      $('#add-form').parsley();

      $('#add-btn').click(function(){
        $('#name').val('');
        $('#description').html('');
        $('#cost').val('');
        $('#cl').val('');
        $('#subBtn').text('Save to Database');
        $('#addModal .modal-title').text('Add Class');
        $('#add-form')[0].reset();
        $('#action').val('add');
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
        $('#add-form')[0].reset();
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: '{{ route('rooms.class.single') }}',
          method: 'POST',
          data: { id:id },
          dataType: 'json',
          success: function(res){
            $('#name').val(res.name);
            $('#description').html(res.description);
            $('#cost').val(res.cost);
            $('#cl').val(id);
            $('#subBtn').text('Save Changes');
            $('#addModal .modal-title').text('Edit Class');
            $('#action').val('edit');
            $('#addModal').modal('show');
          }
        });
      });

      $(document).on('click', '.delete', function(e){
      var id = $(this).attr('count');

      if(confirm('Are you sure you want to delete this Class?')){
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: '{{ route('rooms.class.delete') }}',
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
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <p class="modal-title h5">Add room class</p>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" action="{{ route('rooms.class.action') }}" id="add-form">
          @csrf
          <div class="modal-body">
            <div class="form-group">
              <label for="name">Class name</label>
              <input type="text" id="name" name="name" class="form-control" required data-parsley-trigger="change" data-parsley-required-message="Please provide class name">
            </div>

            <div class="form-group">
              <label for="cost">Cost</label>
              <input type="number" min="0" step="0.01" id="cost" name="cost" class="form-control" required data-parsley-trigger="change" data-parsley-required-message="Please provide the cost">
            </div>

            <div class="form-group">
              <label for="description">Room class</label>
              <textarea id="description" name="description" class="form-control" required data-parsley-trigger="change" data-parsley-required-message="Please select parent category" rows="4">
              </textarea>
            </div>
          </div>
          <div class="modal-footer">
            <div class="form-group d-flex">
              <button type="button" class="btn btn-danger mx-1" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-info mx-1" id="subBtn">Save to Database</button>
              <input type="hidden" name="action" id="action" value="add">
              <input type="hidden" name="cl" id="cl" value="">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  
@endpush