<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SearchHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        $keywords = SearchHistory::selectRaw('count(keyword) as total, keyword')->groupBy('keyword')->get();

        $users = SearchHistory::select('user_id')->with('user')->distinct()->get();
      
        $histories = SearchHistory::orderBy('id', 'desc')->paginate(5);

        return view('search-history', compact('histories', 'keywords', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function search(Request $request)
    {
        $histories = SearchHistory::orderBy('id', 'desc')->with('user');

        if($keywords = $request->keywords){
            $histories =  $histories->whereIn('keyword', $keywords);
        }

        if($users = $request->users){
            $histories =  $histories->whereIn('user_id', $users);
        }

        if($time_range = $request->time_range){
            $date = Carbon::now();
            if($time_range == 'yesterday'){
                $date = Carbon::now()->subDays(1);
            }
            if($time_range == 'week'){
                $date = Carbon::now()->subDays(7);
            }
            if($time_range == 'month'){
                $date = Carbon::now()->subMonths(1);
            }
            $histories =  $histories->where('created_at', '>=', $date);
        }
        elseif($request->start_date){
            $start_date = date('Y-m-d H:i:s', strtotime($request->start_date));
            $end_date = date('Y-m-d H:i:s', strtotime($request->end_date.' 23:59:59'));
            $histories =  $histories->whereBetween('created_at', [$start_date, $end_date]);
        }
        $histories = $histories->paginate(5);

        return view('search-result', compact('histories'))->render();

        // return response()->json($histories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'keyword' => 'required|max:255',
        ]);

        $results = Product::where('tag','LIKE','%'.$request->keyword.'%')->get();

        $history = new SearchHistory($request->all());
        $history->user_id = Auth::user()->id;
        $history->results = json_encode($results);
        $history->save();

        return view('search', compact('results'));
    }

}
