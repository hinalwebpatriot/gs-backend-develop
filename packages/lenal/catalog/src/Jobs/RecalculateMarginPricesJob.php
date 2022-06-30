<?php

namespace lenal\catalog\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\MarginCalculate\Models\MarginCalculate;

class RecalculateMarginPricesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $margin;

    /**
     * Create a new job instance.
     *
     * @param MarginCalculate $margin_calculate
     */
    public function __construct(MarginCalculate $margin_calculate)
    {
        $this->margin = $margin_calculate;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $diamonds = Diamond::searchByMargin($this->margin)
            ->get();

        $diamonds->each(function (Diamond $diamond) {
            $diamond->save();
        });
    }
}
