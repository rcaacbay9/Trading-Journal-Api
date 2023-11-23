<?php

namespace App\Http\Controllers;

use App\Models\Trades;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Nette\Utils\Json;

class TradeController extends Controller
{
    public function getTrades()
    {
        $Trades = Trades::latest('time_executed')
            ->get();

        return new JsonResponse([
            $Trades
        ], 200);
    }
    public function getTradeID($id)
    {
        $Trades = Trades::where('id', $id)
        ->get();

        return new JsonResponse($Trades);
    }
    public function createTrade(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pairs' => 'required|string|max:9',
            'session' => 'required|string|max:10',
            'time_executed' => 'required',
            'position' => 'required|string|max:6',
            'result' => 'required|string|max:7',
            'risk_reward' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return new JsonResponse([
                'message' => $validator->messages(),
                'error' => 'Validate'
            ], 403);
        } else {
            $date = $request->input('time_executed');
            $parsed_date = Carbon::parse($date)->toDateTimeString();
            $comment = $request->input('comment');
            if ($comment == '') {
                $comment = 'N/A';
            } else {
                $comment;
            }
            $createTrade = Trades::create([
                'pairs' => $request->input('pairs'),
                'time_executed' => $parsed_date,
                'session' => $request->input('session'),
                'position' => $request->input('position'),
                'result' => $request->input('result'),
                'risk_reward' => $request->input('risk_reward'),
                'comment' => $comment,
            ]);

            if ($createTrade) {
                return new JsonResponse([
                    'message' => 'Successfully Added!'
                ], 200);
            } else {
                return new JsonResponse([
                    'message' => $createTrade
                ], 404);
            }
        }
    }

    public function getWinrate()
    {
        $win = Trades::where('result', 'WIN')
            ->count();
        $total = Trades::count('result');

        $winrate = round(($win / $total) * 100, 0);
        $loserate = round(100 - $winrate, 0);

        return new JsonResponse([
            'Winrate' => $winrate,
            'Loserate' => $loserate,
            'Total_Trades' => $total
        ], 200);
    }

    public function getPairData()
    {
        $pairs = Trades::groupBy('pairs')
            ->selectRaw('pairs as label, COUNT(*) as count')
            ->get();

        return new JsonResponse($pairs);
    }
    public function getPerformanceData()
    {
        $data = Trades::select(DB::raw('MONTHNAME(time_executed) as x'), DB::raw('count(*) as y'))
            ->groupBy(DB::raw('MONTHNAME(time_executed)'))
            ->orderBy(DB::raw('MONTH(time_executed)'))
            ->get();

        return new JsonResponse($data);
    }

    public function saveEditTrade(Request $request, $id){
        
        $validator = Validator::make($request->all(), [
            'pairs' => 'required|string|max:9',
            'session' => 'required|string|max:10',
            'time_executed' => 'required',
            'position' => 'required|string|max:6',
            'result' => 'required|string|max:7',
            'risk_reward' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return new JsonResponse([
                'message' => $validator->messages(),
                'error' => 'Validate'
            ], 403);
        } else {
            $date = $request->input('time_executed');
            $parsed_date = Carbon::parse($date)->toDateTimeString();
            $updateTrade = Trades::where('id', $id)->update([
                'pairs' => $request->input('pairs'),
                'time_executed' => $parsed_date,
                'session' => $request->input('session'),
                'position' => $request->input('position'),
                'result' => $request->input('result'),
                'risk_reward' => $request->input('risk_reward'),
                'comment' => $request->input('comment') ?: 'N/A',
            ]);

            if ($updateTrade) {
                return new JsonResponse([
                    'message' => 'Edit Save!'
                ], 200);
            } else {
                return new JsonResponse([
                    'message' => 'Error'
                ], 404);
            }
        }
    }
}
