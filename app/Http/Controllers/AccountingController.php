<?php

namespace App\Http\Controllers;

use App\Models\Lawyer;
use App\Models\LawyerTransaction;
use App\Services\LawyerTransactionService;
use Illuminate\Http\Request;

class AccountingController extends Controller
{
    public function __construct(
        protected LawyerTransactionService $transactionService
    ) {
        $this->middleware('can:lawyers.view');
    }

    /**
     * Display accounting dashboard
     */
    public function index(Request $request)
    {
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $lawyerId = $request->get('lawyer_id');

        // Use date range if provided, otherwise all time
        $calcDateFrom = $dateFrom ?? '1970-01-01';
        $calcDateTo = $dateTo ?? now()->addYears(10)->format('Y-m-d');

        // Get lawyers with their financial stats
        $lawyers = $this->transactionService->getAllLawyersStats($calcDateFrom, $calcDateTo, $lawyerId);

        // Overall stats (filtered by lawyer if selected)
        $overallStats = $this->transactionService->getOverallStats($calcDateFrom, $calcDateTo, $lawyerId);

        // Get all lawyers for filter
        $allLawyers = Lawyer::where('active', true)->get();

        return view('pages.accounting.index', [
            'lawyers' => $lawyers,
            'totalIncome' => $overallStats['total_income'],
            'totalExpenses' => $overallStats['total_expenses'],
            'totalProfit' => $overallStats['total_profit'],
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'lawyerId' => $lawyerId,
            'allLawyers' => $allLawyers,
        ]);
    }

    /**
     * Show lawyer transactions
     */
    public function show(Request $request, $lawyerId)
    {
        $lawyer = Lawyer::with('user')->findOrFail($lawyerId);
        
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $type = $request->get('type');

        // Build filters - only add date filters if provided
        $filters = [];
        
        if ($dateFrom) {
            $filters['date_from'] = $dateFrom;
        }
        
        if ($dateTo) {
            $filters['date_to'] = $dateTo;
        }

        if ($type) {
            $filters['type'] = $type;
        }

        // Get transactions with filters
        $transactions = $this->transactionService->getByLawyer($lawyerId, $filters, 20);
        
        // Get all transactions for charts (without pagination)
        $allTransactions = $this->transactionService->getByLawyer($lawyerId, $filters, 0);
        
        // Calculate stats - use date range if provided, otherwise all time
        $statsDateFrom = $dateFrom ?? null;
        $statsDateTo = $dateTo ?? null;
        
        if ($statsDateFrom && $statsDateTo) {
            $stats = $this->transactionService->getLawyerStats($lawyerId, $statsDateFrom, $statsDateTo);
        } else {
            // Get all-time stats
            $stats = $this->transactionService->getLawyerStats(
                $lawyerId, 
                '1970-01-01', 
                now()->addYears(10)->format('Y-m-d')
            );
        }

        return view('pages.accounting.show', [
            'lawyer' => $lawyer,
            'transactions' => $transactions,
            'allTransactions' => $allTransactions,
            'income' => $stats['income'],
            'expenses' => $stats['expenses'],
            'profit' => $stats['profit'],
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'type' => $type,
        ]);
    }

    /**
     * Store a new transaction
     */
    public function store(Request $request)
    {
        $request->validate([
            'lawyer_id' => 'required|exists:lawyers,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0',
            'category' => 'nullable|in:' . implode(',', \App\Models\LawyerTransaction::getCategoryValues()),
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);

        $transaction = $this->transactionService->create($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('accounting.transaction_created_successfully'),
                'transaction' => $transaction
            ]);
        }

        return redirect()->back()->with('success', __('accounting.transaction_created_successfully'));
    }

    /**
     * Delete a transaction
     */
    public function destroy(Request $request, $id)
    {
        try {
            $transaction = LawyerTransaction::findOrFail($id);
            $this->transactionService->delete($transaction);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => __('accounting.transaction_deleted_successfully')
                ]);
            }

            return redirect()->back()->with('success', __('accounting.transaction_deleted_successfully'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }

            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}

