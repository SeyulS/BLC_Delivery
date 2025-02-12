<?php

namespace App\Http\Controllers;

use App\Events\UpdateRevenue;
use App\Models\Loan;
use App\Models\LoanHistory;
use App\Models\Player;
use App\Models\Pinjaman;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingPinjamanController extends Controller
{
    public function index($room_id)
    {
        $room = Room::where('room_id', $room_id)->first();
        return view('Admin.fitur.pinjaman', [
            'room' => $room,
            'players' => Player::where('room_id', $room_id)->get(),
            'loans' => Loan::where('room_id', $room_id)->get(),
            'history' => LoanHistory::where('room_id', $room_id)->get()
        ]);
    }

    public function settingPinjaman(Request $request){
        
        $player = Player::where('player_username', $request->input('player_username'))->first();
        $room = Room::where('room_id', $player->room_id)->first();
        
        if($player->debt == null){
            
            $beforeLoanRevenue = $player->revenue;
            $player->revenue = $player->revenue + $request->input('loanAmount');
            $player->debt = $request->input('loanAmount') + ($request->input('loanAmount')*($request->input('loanInterest'))/100);
            $player->jatuh_tempo = $room->recent_day + $request->input('loanDuration');
            $player->save();

            $loanHistory = new LoanHistory();
            $loanHistory->room_id = $room->room_id;
            $loanHistory->day = $room->recent_day;
            $loanHistory->player_username = $request->input('player_username');
            $loanHistory->loan_value = $request->input('loanAmount');
            $loanHistory->loan_interest = $request->input('loanInterest');
            $loanHistory->loan_due = $request->input('loanDuration');
            $loanHistory->before_loan = $beforeLoanRevenue;
            $loanHistory->after_loan = $player->revenue;
            $loanHistory->save();

            UpdateRevenue::dispatch();
            return response()->json([
                'status' => 'success',
                'message' => 'Pinjaman Success',
                'player_username' => $request->input('player_username'),
                'day' => $room->recent_day,
                'loan_value' => $request->input('loanAmount'),
                'loan_interest' => $request->input('loanInterest'),
                'loan_due' => $request->input('loanDuration'),
                'before_loan' => $beforeLoanRevenue,
                'after_loan' => $player->revenue

            ]);
            
        }
        else{
            return response()->json([
                'status' => 'fail',
                'message' => 'There is still on going debt !!',
            ]);
        }
    }

    public function payDebt(Request $request)
    {
        $player = Player::where('player_username', Auth::guard('player')->user()->player_username)->first();

        if($player->revenue < $request->input('paymentAmount')){
            return response()->json([
                'status' => 'fail',
                'message' => 'Your Cash is not enough !',
                'currentDebt' => $player->debt,
                'loanDue' => $player->jatuh_tempo
            ]);
        }
        else{
            if($request->input('paymentAmount') == $player->debt){
                $player->debt = null;
                $player->jatuh_tempo = null;
                $player->revenue = $player->revenue - $request->input('paymentAmount');
                $player->save();
            }
            else{
                $player->debt = $player->debt - $request->input('paymentAmount');
                $player->revenue = $player->revenue - $request->input('paymentAmount');
                $player->save();
                
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Debt Payment Success !',
                'currentDebt' => $player->debt,
                'loanDue' => $player->jatuh_tempo ?? ''
            ]);
        }

        

        
    }


}
