<?php

namespace lenal\catalog\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\catalog\Models\Diamonds\Manufacturer;
use lenal\MarginCalculate\Models\MarginCalculate;


class DiamondBrandRemoveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'diamonds:remove {manufacture}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove diamonds by manufacture';


    /**
     * @var Manufacturer
     */
    protected $manufacture;

    public function handle()
    {
        $manufactureSlug = $this->argument('manufacture');
        if (!$manufactureSlug) {
            $this->error('Manufacture param is required. diamonds:remove {manufacture}');
            return ;
        }

        $this->manufacture = Manufacturer::query()->where(['slug' => $manufactureSlug])->first();

        if (!$this->manufacture) {
            $this->error('No manufacture slug is wrong! See `manufacture`  table column `slug`');
            return ;
        }

        $this->removeAll();
    }

    private function removeAll()
    {
        $total = $this->totalDiamonds();

        $this->line('Total diamonds: ' . $total);

        if ($total > 0) {
            $this->output->progressStart($total);
            $this->removeDiamonds();
            $this->output->progressFinish();
        }

        $currentTotal = $this->totalDiamonds();

        if (!$currentTotal) {
            $this->line('Start deleting from `margins` table by ' . $this->manufacture->slug);
            MarginCalculate::query()->where('manufacturer_id', $this->manufacture->id)->delete();
            $this->line('Finish deleting from `margins`');

            $this->manufacture->delete();
        } else {
            $this->warn($currentTotal . " diamonds wasn't delete! Manufacture " . $this->manufacture->slug . " wasn't delete too!");
        }
    }

    private function removeDiamonds()
    {
        Diamond::query()->where('manufacturer_id', $this->manufacture->id)->chunk(1000, function(Collection $items) {
            $items->each(function(Diamond $diamond) {
                $diamond->compares()->delete();
                $diamond->reviews()->delete();
                $diamond->staticBlocks()->detach();

                if ($diamond->delete()) {
                    $diamond->clearMediaCollection('diamond-images');
                }

                $this->output->progressAdvance();
            });
        });
    }

    private function totalDiamonds()
    {
        return Diamond::query()->where('manufacturer_id', $this->manufacture->id)->count();
    }
}
