<?php

namespace App\Http\Controllers\Api\v1\Lawyer;

use App\Http\Controllers\Controller;
use App\Http\Resources\LawyerTransactionResource;
use App\Services\LawyerTransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountingController extends Controller
{
    public function __construct(
        protected LawyerTransactionService $transactionService
    ) {
    }

    /**
     * Get lawyer's financial statistics
     * GET /api/v1/lawyer/accounting/stats
     */
    public function stats(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => __('common.unauthenticated')
            ], 401);
        }

        $lawyer = $user->lawyer;
        
        if (!$lawyer) {
            return response()->json([
                'success' => false,
                'message' => __('accounting.lawyer_not_found'),
                'debug' => [
                    'user_id' => $user->id,
                    'user_type_id' => $user->user_type_id,
                    'has_lawyer_profile' => false
                ]
            ], 404);
        }

        $dateFrom = $request->get('date_from') ?? '1970-01-01';
        $dateTo = $request->get('date_to') ?? now()->addYears(10)->format('Y-m-d');

        $stats = $this->transactionService->getLawyerStats($lawyer->id, $dateFrom, $dateTo);

        return response()->json([
            'success' => true,
            'data' => [
                'income' => (float) $stats['income'],
                'expenses' => (float) $stats['expenses'],
                'profit' => (float) $stats['profit'],
                'date_from' => $request->get('date_from'),
                'date_to' => $request->get('date_to'),
            ]
        ]);
    }

    /**
     * Get lawyer's transactions list
     * GET /api/v1/lawyer/accounting/transactions
     */
    public function index(Request $request)
    {
        $lawyer = $request->user()->lawyer;
        
        if (!$lawyer) {
            return response()->json([
                'success' => false,
                'message' => __('accounting.lawyer_not_found')
            ], 404);
        }

        $filters = [];
        
        // Only add date filters if provided
        if ($request->has('date_from') && $request->get('date_from')) {
            $filters['date_from'] = $request->get('date_from');
        }
        
        if ($request->has('date_to') && $request->get('date_to')) {
            $filters['date_to'] = $request->get('date_to');
        }

        if ($request->has('type')) {
            $filters['type'] = $request->get('type');
        }

        if ($request->has('category')) {
            $filters['category'] = $request->get('category');
        }

        $perPage = (int) ($request->get('per_page') ?? 15);
        $transactions = $this->transactionService->getByLawyer($lawyer->id, $filters, $perPage);

        return response()->json([
            'success' => true,
            'data' => LawyerTransactionResource::collection($transactions->items()),
            'pagination' => [
                'current_page' => $transactions->currentPage(),
                'last_page' => $transactions->lastPage(),
                'per_page' => $transactions->perPage(),
                'total' => $transactions->total(),
            ]
        ]);
    }

    /**
     * Get single transaction details
     * GET /api/v1/lawyer/accounting/transactions/{id}
     */
    public function show(Request $request, $id)
    {
        $lawyer = $request->user()->lawyer;
        
        if (!$lawyer) {
            return response()->json([
                'success' => false,
                'message' => __('accounting.lawyer_not_found')
            ], 404);
        }

        $transaction = $this->transactionService->getById($id);

        if (!$transaction || $transaction->lawyer_id != $lawyer->id) {
            return response()->json([
                'success' => false,
                'message' => __('accounting.transaction_not_found')
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new LawyerTransactionResource($transaction)
        ]);
    }

    /**
     * Create new transaction
     * POST /api/v1/lawyer/accounting/transactions
     */
    public function store(Request $request)
    {
        $lawyer = $request->user()->lawyer;
        
        if (!$lawyer) {
            return response()->json([
                'success' => false,
                'message' => __('accounting.lawyer_not_found')
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0',
            'category' => 'nullable|in:' . implode(',', \App\Models\LawyerTransaction::getCategoryValues()),
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => __('common.validation_error'),
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        $data['lawyer_id'] = $lawyer->id;

        $transaction = $this->transactionService->create($data);

        return response()->json([
            'success' => true,
            'message' => __('accounting.transaction_created_successfully'),
            'data' => new LawyerTransactionResource($transaction)
        ], 201);
    }

    /**
     * Update transaction
     * PUT /api/v1/lawyer/accounting/transactions/{id}
     */
    public function update(Request $request, $id)
    {
        $lawyer = $request->user()->lawyer;
        
        if (!$lawyer) {
            return response()->json([
                'success' => false,
                'message' => __('accounting.lawyer_not_found')
            ], 404);
        }

        $transaction = $this->transactionService->getById($id);

        if (!$transaction || $transaction->lawyer_id != $lawyer->id) {
            return response()->json([
                'success' => false,
                'message' => __('accounting.transaction_not_found')
            ], 404);
        }

        // Cannot update appointment-linked transactions
        if ($transaction->appointment_id) {
            return response()->json([
                'success' => false,
                'message' => __('accounting.cannot_update_appointment_transaction')
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'type' => 'sometimes|required|in:income,expense',
            'amount' => 'sometimes|required|numeric|min:0',
            'category' => 'nullable|in:' . implode(',', \App\Models\LawyerTransaction::getCategoryValues()),
            'description' => 'nullable|string',
            'transaction_date' => 'sometimes|required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => __('common.validation_error'),
                'errors' => $validator->errors()
            ], 422);
        }

        $updatedTransaction = $this->transactionService->update($transaction, $validator->validated());

        return response()->json([
            'success' => true,
            'message' => __('accounting.transaction_updated_successfully'),
            'data' => new LawyerTransactionResource($updatedTransaction)
        ]);
    }

    /**
     * Delete transaction
     * DELETE /api/v1/lawyer/accounting/transactions/{id}
     */
    public function destroy(Request $request, $id)
    {
        $lawyer = $request->user()->lawyer;
        
        if (!$lawyer) {
            return response()->json([
                'success' => false,
                'message' => __('accounting.lawyer_not_found')
            ], 404);
        }

        $transaction = $this->transactionService->getById($id);

        if (!$transaction || $transaction->lawyer_id != $lawyer->id) {
            return response()->json([
                'success' => false,
                'message' => __('accounting.transaction_not_found')
            ], 404);
        }

        try {
            $this->transactionService->delete($transaction);

            return response()->json([
                'success' => true,
                'message' => __('accounting.transaction_deleted_successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Get transactions grouped by category
     * GET /api/v1/lawyer/accounting/by-category
     */
    public function byCategory(Request $request)
    {
        $lawyer = $request->user()->lawyer;
        
        if (!$lawyer) {
            return response()->json([
                'success' => false,
                'message' => __('accounting.lawyer_not_found')
            ], 404);
        }

        $filters = [];
        
        if ($request->has('date_from') && $request->get('date_from')) {
            $filters['date_from'] = $request->get('date_from');
        }
        
        if ($request->has('date_to') && $request->get('date_to')) {
            $filters['date_to'] = $request->get('date_to');
        }

        $transactions = $this->transactionService->getByLawyer($lawyer->id, $filters, 0);
        
        $categoryData = $transactions->groupBy('category')->map(function($items) {
            return [
                'total' => (float) $items->sum('amount'),
                'count' => $items->count()
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $categoryData
        ]);
    }

    /**
     * Get income vs expenses chart data
     * GET /api/v1/lawyer/accounting/chart-data
     */
    public function chartData(Request $request)
    {
        $lawyer = $request->user()->lawyer;
        
        if (!$lawyer) {
            return response()->json([
                'success' => false,
                'message' => __('accounting.lawyer_not_found')
            ], 404);
        }

        $dateFrom = $request->get('date_from') ?? '1970-01-01';
        $dateTo = $request->get('date_to') ?? now()->addYears(10)->format('Y-m-d');

        $stats = $this->transactionService->getLawyerStats($lawyer->id, $dateFrom, $dateTo);

        return response()->json([
            'success' => true,
            'data' => [
                'income' => (float) $stats['income'],
                'expenses' => (float) $stats['expenses'],
                'profit' => (float) $stats['profit'],
            ]
        ]);
    }

    /**
     * Get available transaction categories
     * GET /api/v1/lawyer/accounting/categories
     */
    public function categories(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => \App\Models\LawyerTransaction::getCategories()
        ]);
    }
}
