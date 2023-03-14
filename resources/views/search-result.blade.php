<div class="result">
    @foreach ($histories as $history)
    <div class="row">
        <div class="col-4">
            <h3 class="fw-bold" >{{ $history->keyword }}</h3>
            <p>User : <span  class="fw-bold" >{{ $history->user->name }}</span></p>
        </div>
        <div class="col-8">
            <h4>Search results</h4>
            @foreach (json_decode($history->results) as $result)
            <div class="card m-3 p-4">
                <p class="fw-bold">{{ $result->name }}</p>
                Brand: {{ $result->brand }} ||
                Category: {{ $result->category }}
            </div>
            @endforeach
        </div>
        <p class="text-end">{{ $history->created_at->diffForHumans() }}</p>

    </div>
       
        <hr>
    @endforeach
    
    
</div>
<div id="pagination">{!! $histories->links() !!}</div>
