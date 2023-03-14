@extends('layouts.app')

@section('content')
      <div class="row">
        <div class="col-3 bg-white p-5">
            <form id="historyForm">
                @csrf

                <p class="fw-bold">Keywords</p>
                @foreach ($keywords as $keyword)
                <div class="form-check">
                    <label class="m-checkbox">
                        <input name="keyword[]" type="checkbox" value="{{ $keyword->keyword }}" class="form-check-input">
                        {{ $keyword->keyword  }} ({{$keyword->total}} times found )
                    </label>
                </div>
                @endforeach
                
                <hr>

                <p class="fw-bold">Users</p>
                @foreach ($users as $user)
                <div class="form-check">
                    <label  class="m-checkbox">
                        <input name="user[]" type="checkbox" value="{{ $user->user_id }}" class="form-check-input">
                        {{ $user->user->name }}

                    </label>
                </div>
                @endforeach

                <hr>
                <p class="fw-bold">Time Range</p>
                <div class="form-check">
                    <input type="radio" id="yesterday" name="time_range" value="yesterday" class="form-check-input"> 
                    <label for="yesterday" class="form-check-label">See data from yesterday</label><br>
                    <input type="radio" id="week" name="time_range" value="week" class="form-check-input">
                    <label for="week" class="form-check-label">See data from last week</label><br>
                    <input type="radio" id="month" name="time_range" value="month" class="form-check-input">
                    <label for="month" class="form-check-label"> see data from last month</label>
                    </div>
                <hr>

                <p class="fw-bold">Select Date:</p>
                
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input class="form-control my-2" type="date" id="start_date" name="start_date">
            
                    <label for="end_date">End Date:</label>
                    <input  class="form-control my-2" type="date" id="end_date" name="end_date">
                </div>

                <hr>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-block" id="filter">Filter</button>
                </div>
            </form>
        </div>
        <div class="col-9 p-5">
            <h3 class="pb-4 fw-bold">Search History</h3>
            <hr>
            <div id="resultData">
                @include('search-result')
            </div>
        </div>
      </div>

    <script>
        $( document ).ready(function() {
            var keywords = [];
            var users = [];
            $(document).on('click', '.pagination a', function(e){
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                searchHistory(page);
               
            });

            function searchHistory(page) {
                
                keywords = []; 
                users = []; 

                $('input[name="keyword[]"]:checked').each(function()
                {
                    keywords.push($(this).val());
                }); 

                $('input[name="user[]"]:checked').each(function()
                {
                    users.push($(this).val());
                });
        
                time_range = $('input[name="time_range"]:checked').val();
                start_date = $('#start_date').val();
                end_date = $('#end_date').val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: '/ajax-search'+ "?page="+page,
                    data: { 
                        keywords, users, time_range, start_date, end_date
                    },
                    error: function(e) {
                        console.log(e.responseText);
                    },
                    success: function (data) {
                        $('#resultData').html(data);
                        
                    }
                });

            }

            $('#historyForm').submit(function(event){  
                event.preventDefault()
                let page = 1;
                searchHistory(page);
                
            });
     
        });
    </script>
@endsection