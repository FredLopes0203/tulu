<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Access\User\User;
use Illuminate\Http\Request;
use App\Models\Charity;
use App\Models\Charitygroup;
use App\Models\Userdonates;

/**
 * Class DashboardController.
 */
class TransactionController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $charities = Charity::where('id', '>', 0)->get();
        $charitygroups = Charitygroup::where('id', '>', 0)->get();
        return view('backend.transaction')->with(['charitygroups' => $charitygroups]);
    }

    public function getTransactions(Request $request)
    {
        $selectedCharityGroup = $request->input('charitygroup');
        $selectedCharity = $request->input('charity');
        $selectedUser = $request->input('selectedUser');

        $transactions = Userdonates::where('id', '>', 0);

        if($selectedUser > 0)
        {
            $transactions = $transactions->where('userid', $selectedUser);
        }

        if($selectedCharity > 0)
        {
            $transactions = $transactions->where('charity', $selectedCharity);
        }
        else
        {
            if($selectedCharityGroup > 0)
            {
                $charities = Charity::where('charity_groupid', $selectedCharityGroup)->get();

                if($charities->count() > 0)
                {
                    $transactions = $transactions->where(function($transactionQuery) use ($charities)
                    {
                        $charityCount = 0;

                        foreach($charities as $charity)
                        {
                            if($charityCount == 0)
                            {
                                $transactionQuery->where('charity', $charity->id);
                            }
                            else
                            {
                                $transactionQuery->orwhere('charity', $charity->id);
                            }
                            $charityCount = $charityCount + 1;
                        }
                    });
                }
                else
                {
                    return response()->json(['transactions' => []]);
                }
            }
        }
        $transactions = $transactions->orderBy('created_at', 'DESC')->get();

        $total = 0;
        foreach ($transactions as $transaction)
        {
            $charity = Charity::where('id', $transaction->charity)->first();
            $charitygroup = Charitygroup::where('id', $charity->charity_groupid)->first();
            $userinfo = User::where('id', $transaction->userid)->first();

            $transaction['charityname'] = $charity->name;
            $transaction['charitygroup'] = $charitygroup->name;
            $transaction['username'] = $userinfo->real_name;
            $transaction['heroname'] = $userinfo->hero_name;
            $total = $total + $transaction['amount'];
        }
        return response()->json(['transactions' => $transactions, 'total' => $total]);
    }
}
