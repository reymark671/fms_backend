<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientSpendingPlan;
use App\Models\Client;
use App\Models\ServiceCode;
use PDF; 
use App\Models\ClientSpendingPlanItems;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
class ClientSpendingPlanController extends Controller
{
    //
    public function index()
    {
        $clientSpendingPlans = ClientSpendingPlan::with('client')->get();
        $clients = Client::all();
        $serviceCodes = ServiceCode::all();
        return view('pages.client_spending_plan', compact('clientSpendingPlans','clients','serviceCodes'));
    }

    public function create()
    {
        $clients = Client::all();
        $serviceCodes = ServiceCode::all();
        return view('pages.client_spending_plan.create', compact('clients', 'serviceCodes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'total_budget' => 'required|numeric',
            'from' => 'required|date',
            'to' => 'required|date',
            'service_codes' => 'required|array',
            'service_codes.*.service_code_id' => 'required|exists:service_codes,id',
            'service_codes.*.allocated_budget' => 'required|numeric',
        ]);

        $spendingPlan = ClientSpendingPlan::create([
            'client_id' => $data['client_id'],
            'from' => $data['from'],
            'to' => $data['to'],
            'total_budget' => $data['total_budget'],
        ]);

        foreach ($data['service_codes'] as $serviceCode) {
            ClientSpendingPlanItems::create([
                'client_spending_plan_id' => $spendingPlan->id,
                'service_code_id' => $serviceCode['service_code_id'],
                'allocated_budget' => $serviceCode['allocated_budget'],
            ]);
        }

        return redirect()->route('client-spending-plan.index')->with('success', 'Spending Plan created successfully');
    }
    public function download($id)
    {
        $clientplan = ClientSpendingPlan::findOrFail($id);
        $clientplan->load('items.serviceCode.category');
        $groupedItems = $clientplan->items->groupBy('serviceCode.category.id');
        $grandTotalAllocated = $clientplan->items->sum('allocated_budget');
        $grandTotalUsed = $clientplan->items->sum('used_budget');
        
        $pdf = PDF::loadView('pdf.client_spending_plan', compact('clientplan', 'groupedItems', 'grandTotalAllocated', 'grandTotalUsed'));
        $pdf->setPaper('a4');
        return $pdf->download('client_spending_plan_' . $clientplan->id . '.pdf');
    }
}
