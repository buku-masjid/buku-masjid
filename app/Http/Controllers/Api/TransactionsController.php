<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transactions\CreateRequest;
use App\Http\Requests\Transactions\UpdateRequest;
use App\Http\Resources\Transaction as TransactionResource;
use App\Http\Resources\TransactionCollection;
use App\Transaction;

class TransactionsController extends Controller
{
    /**
     * Return a listing of the transaction.
     *
     * @return \App\Http\Resources\TransactionCollection
     */
    public function index()
    {
        $yearMonth = $this->getYearMonth();

        return new TransactionCollection(
            $this->getTansactions($yearMonth)
        );
    }

    /**
     * Store a newly created transaction in storage.
     *
     * @param  \App\Http\Requests\Transactions\CreateRequest  $transactionCreateForm
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateRequest $transactionCreateForm)
    {
        $transaction = $transactionCreateForm->save();

        $responseMessage = __('transaction.income_added');

        if ($transaction['in_out'] == 0) {
            $responseMessage = __('transaction.spending_added');
        }

        $responseData = [
            'message' => $responseMessage,
            'data' => new TransactionResource($transaction),
        ];

        return response()->json($responseData, 201);
    }

    /**
     * Show the specified transaction data.
     *
     * @param  \App\Transaction  $transaction
     * @return \App\Http\Controllers\Api\TransactionResource
     */
    public function show(Transaction $transaction)
    {
        return new TransactionResource($transaction);
    }

    /**
     * Update the specified transaction in storage.
     *
     * @param  \App\Http\Requests\Transactions\UpdateRequest  $transactionUpdateForm
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $transactionUpdateForm, Transaction $transaction)
    {
        $transaction = $transactionUpdateForm->save();

        return response()->json([
            'message' => __('transaction.updated'),
            'data' => new TransactionResource($transaction),
        ]);
    }

    /**
     * Remove the specified transaction from storage.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);

        request()->validate(['transaction_id' => 'required']);

        if (request('transaction_id') == $transaction->id && $transaction->delete()) {
            return response()->json(['message' => __('transaction.deleted')]);
        }

        return response()->json(['message' => __('transaction.undeleted')]);
    }
}
