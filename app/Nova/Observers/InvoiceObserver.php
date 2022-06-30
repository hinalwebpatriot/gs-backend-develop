<?php

namespace App\Nova\Observers;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use lenal\catalog\Mail\ServiceInvoiceMail;
use lenal\catalog\Models\Invoice;
use lenal\catalog\Models\InvoiceService;

class InvoiceObserver
{
    public function saved(Invoice $invoice)
    {
        $serviceItems = request()->get('services_items');

        if (!$serviceItems) {
            $invoice->services()->delete();
            return ;
        }
        Log::info($serviceItems);

        $serviceItems = collect(json_decode($serviceItems, true));

        Log::info($serviceItems);
        foreach ($invoice->services as $key => $service) {
            $item = $serviceItems->firstWhere('id', $service->id);
            if (!$item) {
                $service->delete();
                $invoice->services->forget($key);
            }
        }

        foreach ($serviceItems as $item) {
            /** @var InvoiceService $service */
            if (!isset($item['id'])) {
                $invoice->services()->create($item);
            } else {
                $service = $invoice->services->firstWhere('id', $item['id']);
                if ($service) {
                    $service->update($item);
                }
            }
        }

        $invoice->load('services');
        $invoice->calculatePrices();

        Invoice::withoutEvents(function() use ($invoice) {
            $invoice->save();
        });

        if ($invoice->email && !$invoice->isSentMail() && $invoice->services->count() > 0) {
            $invoice->is_sent = 1;
            $invoice->save();

            Mail::to($invoice->email)->send(new ServiceInvoiceMail($invoice));
        }
    }
}