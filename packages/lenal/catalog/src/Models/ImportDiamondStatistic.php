<?php

namespace lenal\catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @property integer $id
 * @property integer $manufacturer_id
 * @property integer $ymd
 * @property integer $create_job
 * @property integer $update_job
 * @property integer $delete_job
 * @property integer $create_request
 * @property integer $update_request
 * @property integer $delete_request
 * @property integer $created
 * @property integer $updated
 * @property integer $deleted
 * @property integer $create_err
 * @property integer $update_err
 * @property integer $delete_err
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class ImportDiamondStatistic extends Model
{
    protected $fillable = ['manufacturer_id', 'ymd'];

    /**
     * @param int|string $manufacturerId
     * @return ImportDiamondStatistic|\Illuminate\Database\Eloquent\Builder|Model
     * @throws \Exception
     */
    public static function findOne($manufacturerId)
    {
        if (!is_numeric($manufacturerId)) {
            $slug = $manufacturerId;
            $manufacturer = DB::table('manufacturers')->where('slug', $slug)->first();
            $manufacturerId = $manufacturer ? $manufacturer->id : 0;

            if (!$manufacturerId) {
                logger()->channel('fail-diamonds')->debug('Wrong manufacturer slug = ' . $slug);
            }
        }

        $ymd = date('Ymd');

        try {
            $statistic = static::query()
                ->where(['manufacturer_id' => $manufacturerId, 'ymd' => $ymd])
                ->first();

            if (!$statistic) {
                DB::beginTransaction();

                $statistic = static::query()->create(['manufacturer_id' => $manufacturerId, 'ymd' => $ymd]);

                DB::commit();
            }

            return $statistic;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function format()
    {
        return [
            'create' => $this->formatLine('create'),
            'update' => $this->formatLine('update'),
            'delete' => $this->formatLine('delete'),
        ];
    }

    private function formatLine($param)
    {
        $all = $this->{$param . '_job'};
        $requested = $this->{$param . '_request'};
        $success = $this->{$param . 'd'};
        $error = $this->{$param . '_err'};

        if ($all == $requested && $all == $success) {
            $prefix = 'GOOD';
        } else {
            $prefix = 'FAIL';
        }

        return $prefix. ' | all: ' . $all . ', ' .
            'requested: ' . $requested  . ', ' .
            $param. 'd: ' . $success . ', ' .
            'error: ' . $error;
    }
}
