<?php

namespace lenal\catalog\Controllers;


use App\Http\Controllers\Controller;
use lenal\catalog\Models\Invoice;
use lenal\catalog\Resources\InvoiceResource;

class InvoicesController extends Controller
{
    public function invoice($alias)
    {
        /** @var Invoice $invoice */
        $invoice = Invoice::query()
            ->with('services')
            ->where('alias', $alias)
            ->firstOrFail();

        $invoice->openedStatus();

        return response()->json(new InvoiceResource($invoice));
    }
}