<?php

namespace App\Http\Controllers;

use App\Loan;
use App\Transaction;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    /**
     * Display a listing of the loan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $loanQuery = Loan::query();
        if ($request->get('q')) {
            $loanQuery->where('description', 'like', '%'.$request->get('q').'%');
        }
        if ($request->get('type_id')) {
            $loanQuery->where('type_id', 'like', '%'.$request->get('type_id').'%');
        }
        $loans = $loanQuery->with(['partner'])->latest()->paginate(25);

        return view('loans.index', compact('loans'));
    }

    /**
     * Show the form for creating a new loan.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('create', new Loan);
        $partners = $this->getPartnerList();
        $loanTypes = [
            Loan::TYPE_DEBT       => __('loan.types.debt'),
            Loan::TYPE_RECEIVABLE => __('loan.types.receivable'),
        ];

        return view('loans.create', compact('partners', 'loanTypes'));
    }

    /**
     * Store a newly created loan in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->authorize('create', new Loan);

        $newLoan = $request->validate([
            'partner_id'            => 'required|exists:partners,id',
            'type_id'               => 'required|in:'.Loan::TYPE_DEBT.','.Loan::TYPE_RECEIVABLE,
            'amount'                => 'required|numeric',
            'planned_payment_count' => 'required|numeric',
            'description'           => 'required|max:255',
            'start_date'            => 'nullable|date_format:Y-m-d',
            'end_date'              => 'nullable|date_format:Y-m-d',
        ]);
        $newLoan['creator_id'] = auth()->id();

        $loan = Loan::create($newLoan);
        $newTransaction = [
            'loan_id'     => $loan->id,
            'in_out'      => $loan->type_id == Loan::TYPE_DEBT ? 1 : 0, // 0:spending, 1:income
            'amount'      => $loan->amount,
            'date'        => $loan->start_date ?: $loan->created_at->format('Y-m-d'),
            'description' => $loan->description,
            'partner_id'  => $loan->partner_id,
            'creator_id'  => $loan->creator_id,
        ];
        Transaction::create($newTransaction);

        return redirect()->route('loans.show', $loan);
    }

    /**
     * Display the specified loan.
     *
     * @param  \App\Loan  $loan
     * @return \Illuminate\View\View
     */
    public function show(Loan $loan)
    {
        $transactions = $loan->transactions;
        $inOutOptions = [__('loan.pay_debt'), __('loan.add_debt')];
        $defaultInOutValue = 0;
        if ($loan->type_id == Loan::TYPE_RECEIVABLE) {
            $inOutOptions = [
                1 => $loan->partner->name.' '.__('loan.pay_debt'),
                0 => $loan->partner->name.' '.__('loan.add_debt'),
            ];
            $defaultInOutValue = 1;
        }

        return view('loans.show', compact('loan', 'transactions', 'inOutOptions', 'defaultInOutValue'));
    }

    /**
     * Show the form for editing the specified loan.
     *
     * @param  \App\Loan  $loan
     * @return \Illuminate\View\View
     */
    public function edit(Loan $loan)
    {
        $this->authorize('update', $loan);
        $partners = $this->getPartnerList();
        $loanTypes = [
            Loan::TYPE_DEBT       => __('loan.types.debt'),
            Loan::TYPE_RECEIVABLE => __('loan.types.receivable'),
        ];

        return view('loans.edit', compact('loan', 'partners', 'loanTypes'));
    }

    /**
     * Update the specified loan in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Loan  $loan
     * @return \Illuminate\Routing\Redirector
     */
    public function update(Request $request, Loan $loan)
    {
        $this->authorize('update', $loan);

        $loanData = $request->validate([
            'partner_id'            => 'required|exists:partners,id',
            'type_id'               => 'required|in:'.Loan::TYPE_DEBT.','.Loan::TYPE_RECEIVABLE,
            'amount'                => 'required|numeric',
            'planned_payment_count' => 'required|numeric',
            'description'           => 'required|max:255',
            'start_date'            => 'nullable|date_format:Y-m-d',
            'end_date'              => 'nullable|date_format:Y-m-d',
        ]);
        $loan->update($loanData);
        $transaction = $loan->transactions()->orderBy('created_at')->first();
        if ($transaction) {
            $transaction->partner_id = $loan->partner_id;
            $transaction->amount = $loan->amount;
            $transaction->date = $loan->start_date;
            $transaction->description = $loan->description;
            $transaction->in_out = $loan->type_id == Loan::TYPE_DEBT ? 1 : 0; // 0:spending, 1:income
            $transaction->save();
        }

        return redirect()->route('loans.show', $loan);
    }

    /**
     * Remove the specified loan from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Loan  $loan
     * @return \Illuminate\Routing\Redirector
     */
    public function destroy(Request $request, Loan $loan)
    {
        $this->authorize('delete', $loan);

        $request->validate(['loan_id' => 'required']);

        if ($request->get('loan_id') == $loan->id && $loan->delete()) {
            return redirect()->route('loans.index');
        }

        return back();
    }
}
