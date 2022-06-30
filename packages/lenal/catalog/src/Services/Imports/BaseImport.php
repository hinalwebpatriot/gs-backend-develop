<?php

namespace lenal\catalog\Services\Imports;


use App\Helpers\Tools;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use lenal\catalog\Models\Products\Brand;
use lenal\catalog\Models\Rings\Metal;

abstract class BaseImport
{
    protected $filename;
    protected $path;

    private $brands;
    private $metals;

    protected $imagesPath = [];

    public function __construct()
    {
        $this->path = storage_path('app/import-source/' . $this->filename);

        $this->brands = Brand::query()->get()->pluck('id', 'slug')->toArray();
        $this->metals = Metal::query()->get()->pluck('id', 'slug')->toArray();
    }

    public function getItems()
    {
        if (($resource = fopen($this->path, 'r')) !== false) {
            $header = collect(fgetcsv($resource, 0, ';'))
                ->map(function ($header_item) {
                    return trim($header_item);
                });

            while (($data = fgetcsv($resource, 0, ';')) !== false) {
                yield $data;
            }

            fclose($resource);
        }
    }

    abstract protected function saveItem(array $item);

    protected function formatPrice($value)
    {
        return trim(str_replace(['$', ' ', ','], '', $value));
    }

    protected function getOrCreateBrandId($brand)
    {
        $brandSlug = strtolower($brand);

        if (isset($this->brands[$brandSlug])) {
            return $this->brands[$brandSlug];
        }

        /** @var Brand $brandModel */
        $brandModel = Brand::query()->create([
            'slug' => $brandSlug,
            'title' => ['en' => $brand],
        ]);

        $this->brands[$brandModel->slug] = $brandModel->id;

        return $brandModel->id;
    }

    protected function getMetalId($metal)
    {
        $metalSlug = Str::slug($metal);

        return $this->metals[$metalSlug] ?? '';
    }

    protected function getMetalAbbreviation($metal)
    {
        $result = '';
        foreach (explode(' ', $metal) as $index => $item) {
            if ($index > 0) {
                $result .= substr($item, 0, 1);
            }
        }

        return $result;
    }

    protected function encodingValue($value)
    {
        return mb_convert_encoding(trim($value), 'UTF-8', 'windows-1251');
    }

    protected function collectImages($dir)
    {
        $path = storage_path('app/import-source/images/' . $dir);

        if (is_dir($path)) {
            $this->imagesPath[] = $path;
            $files = File::allFiles($path);

            $result = [];

            foreach ($files as $file) {
                $result[] = $file->getPathname();
            }

            return $result;
        }

        return [];
    }

    protected function countPhotos($basePath, $imageName)
    {
        $count = 0;
        foreach (['Render File', 'Renders', 'Render'] as $subFolder) {
            $path = storage_path('app/all-products-images/' . str_replace('{folder}', $subFolder, $basePath));
            $path .= '/' . $imageName;

            foreach (['S', 'F', '34'] as $suffix) {
                $filename = $path . '_' . $suffix . '.jpg';
                echo $filename . '<br>---------------<br>';

                if (is_file($filename)) {
                    $count++;
                }
            }
        }

        return $count;
    }

    protected function extractImages($files)
    {
        return array_filter($files, function($filename) {
            return strrchr($filename, '.') == '.jpg';
        });
    }

    protected function extractVideos($files)
    {
        return array_filter($files, function($filename) {
            return strrchr($filename, '.') == '.mp4';
        });
    }

}