<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Transaction extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $transaction = $this->resource;

        return [
            'id' => $transaction->id,
            'date' => $transaction->date,
            'amount' => (float) $transaction->amount,
            'amount_string' => $transaction->amount_string,
            'description' => $transaction->description,
            'in_out' => (int) $transaction->in_out,
            'book_id' => $transaction->book_id,
            'book' => $transaction->book->name,
            'category_id' => $transaction->category_id,
            'category' => optional($transaction->category)->name,
            'category_color' => optional($transaction->category)->color,
            'created_at' => $transaction->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $transaction->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
