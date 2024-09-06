<span class="float-right">{{ $transaction->amount_string }}</span>
{{ $transaction->date }}
<div>
    {{ $transaction->description }}
    <span class="float-right">
        {{ link_to_route('transactions.show', __('app.show'), $transaction, ['class' => 'btn btn-secondary btn-sm']) }}
    </span>
</div>
<div style="margin-bottom: 6px;">
    <span class="badge {{ $transaction->bankAccount->exists ? 'bg-purple' : 'bg-gray'}}">
        {{ $transaction->bankAccount->name }}
    </span>
</div>
<hr style="margin: 6px 0">
