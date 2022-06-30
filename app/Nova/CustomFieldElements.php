<?php


namespace App\Nova;


use DebugBar\DebugBar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Fields\Text;
use lenal\catalog\Models\Products\ProductField;

trait CustomFieldElements
{
    protected function customFieldValues()
    {
        if (request()->route()->hasParameter('resourceId') && request()->isMethod('get')) {
            /** @var Model $ring */
            $entity = static::$model::query()->find(request()->route('resourceId'));
            return $entity->customFields->pluck('value', 'product_field_id')->toArray();
        }

        return [];
    }

    protected function collectCustomFields($category)
    {
        return $this->prepareCustomFormFields(ProductField::findByCategory($category));
    }

    protected function prepareCustomFormFields($customFields)
    {
        $fields = [];
        $fieldValues = $this->customFieldValues();

        foreach ($customFields as $customField) {
            $fields[] = Text::make($customField->label, 'custom_fields_collection[' . $customField->id . ']')
                ->withMeta(['value' => $fieldValues[$customField->id] ?? ''])
                ->hideFromIndex();
        }

        return $fields;
    }
}