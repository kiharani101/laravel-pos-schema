@extends('layouts.master')

@section('content')

<main class="app-content">
  <div class="card">
    <div class="card-header h3">
      Rooms List
      <button type="button" class="btn btn-success pull-right" id="add-btn"><i class="fa fa-plus-square mr-1"></i>Add</button>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-sm table-bordered" id="table">
          <thead>
            <tr>
              <th>Room Number</th>
              <th>Class</th>
              <th>Cost</th>
              <th>Status</th>
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
        "ajax": "{{ route('rooms.api') }}",
        "columns": [
          { "data": "number"},
          { "data": "class", orderable: false, searchable: false},
          { "data": "cost", orderable: false, searchable: false},
          { "data": "status"},
          { "data": "action", orderable: false, searchable: false}
        ]
      });

      $('#add-form').parsley();

      $('#add-btn').click(function(){
        $('#room_number').val('');
        $('#ri').val('');
        $('#pr').val('');
        $('#pr').text('-- Select room class --');
        $('#subBtn').text('Save to Database');
        $('#addModal .modal-title').text('Add Room');
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
          url: '{{ route('rooms.single') }}',
          method: 'POST',
          data: { id:id },
          dataType: 'json',
          success: function(res){
            $('#room_number').val(res.number);
            $('#pr').val(res.class_id);
            $('#pr').text(res.class_name);
            $('#ri').val(id);
            $('#subBtn').text('Save Changes');
            $('#addModal .modal-title').text('Edit Room');
            $('#action').val('edit');
            $('#addModal').modal('show');
          }
        });
      });

      $(document).on('click', '.delete', function(e){
      var id = $(this).attr('count');

      if(confirm('Are you sure you want to delete this Category?')){
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: '{{ route('rooms.delete') }}',
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
          <p class="modal-title h5">Add Room</p>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" action="{{ route('rooms.action') }}" id="add-form">
          @csrf
          <div class="modal-body">
            <div class="form-group">
              <label for="room_number">Room number</label>
              <input type="text" id="room_number" name="room_number" class="form-control" required data-parsley-trigger="change" data-parsley-required-message="Please provide room number">
            </div>

            <div class="form-group">
              <label for="room_class">Room class</label>
              <select id="room_class" name="room_class" class="form-control" required data-parsley-trigger="change" data-parsley-required-message="Please select room class">
                <option id="pr" value="">-- Select room class --</option>
                @forelse ($classes as $class)
                  <option value="{{ $class->id }}">{{ $class->name }}</option>
                @empty
                  <option value="">No data found</option>
                @endforelse
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <div class="form-group d-flex">
              <button type="button" class="btn btn-danger mx-1" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-info mx-1" id="subBtn">Save to Database</button>
              <input type="hidden" name="action" id="action" value="add">
              <input type="hidden" name="cat" id="cat" value="">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  
@endpush