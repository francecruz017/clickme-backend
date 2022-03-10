<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Counter;
use Carbon\Carbon;

class ClickMeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'times_clicked' => $this->getCountsOfClicks(),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $counters = Counter::get()
            ->where('created_at', '>=', Carbon::today());

        if ($counters->isEmpty()) {
            $counter = new Counter;
            $counter->times_clicked = 1;
        } else {
            $counter = $counters->first();
            $counter->times_clicked = ++$counter->times_clicked;
        }

        $counter->save();

        return response()->json([
            'times_clicked' => $this->getCountsOfClicks(),
        ], 200);
    }

    /**
     *
     * @return int
     */
    public function getCountsOfClicks()
    {
        $counters = Counter::get()
            ->where('created_at', '>=', Carbon::today());

        if ($counters->isNotEmpty()) {
            return $counters->first()->times_clicked;
        }
        
        return 0;
    }
}
