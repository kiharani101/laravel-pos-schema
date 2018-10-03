@extends('layouts.master')

@section('content')

<main class="app-content">

  <div class="app-title">
    <div>
      <h1><i class="fa fa-dashboard"></i> Blank Page</h1>
      <p>Start a beautiful journey here</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item"><a href="#">Blank Page</a></li>
    </ul>
  </div>

  <div class="card">
    <div class="card-header">
      <p class="h3">Jay's My tasks today</p>
    </div>
    <div class="card-body">
      <li class="list-group-item">Eat</li>
      <li class="list-group-item">Drink</li>
      <li class="list-group-item">Party</li>
    </div>
  </div>
</main>

@endsection

@push('css')
  {{--  --}}
@endpush
 
@push('scripts')
  {{--  --}}
@endpush

