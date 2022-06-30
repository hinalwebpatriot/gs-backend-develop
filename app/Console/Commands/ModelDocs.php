<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class ModelDocs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'model-docs {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generating class model php-docs by table';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $table = $this->input->getArgument('table');
        $columns = Schema::getColumnListing($table);

        $commentDoc = "/**\n";
        foreach ($columns as $column) {
            $columnType = Schema::getColumnType($table, $column);

            if ($columnType == 'datetime') {
                if ($column == 'created_at' || $column == 'updated_at') {
                    $type = '\Carbon\Carbon';
                } else {
                    $type = 'string';
                }
            } elseif ($columnType == 'decimal') {
                $type = 'number';
            } elseif (in_array($columnType, ['smallint', 'bigint', 'boolean'])) {
                $type = 'integer';
            } elseif (in_array($columnType, ['text', 'mediumtext'])) {
                $type = 'string';
            } else {
                $type = $columnType;
            }
            $commentDoc .= "* @property $type \$$column\n";
        }
        $commentDoc .= "*/\n";

        echo $commentDoc;
        return true;
    }
}
