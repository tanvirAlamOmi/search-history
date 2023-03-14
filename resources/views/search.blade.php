@extends('layouts.app')

@section('content')
    <div class="container my-5 py-5 px-5">
    <!-- Search input -->
    <form action="{{ route('search') }}" method="POST">
        @csrf
        <input type="search" class="form-control" placeholder="Search here" name="keyword">
    </form>
    <!-- List items -->
    @if(! empty($results))
    <ul class="list-group mt-3">
        @foreach($results as $result)
            <li class="list-group-item">{{$result->name}}</li>
            @endforeach
        </ul>
    @endif
    
</div>
@endsection