<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "order_id"=> "numeric|nullable",
            '*.product_id'=> 'required|exists:products,id',
            '*.quantity'=> 'required|numeric'
        ];
    }
}
