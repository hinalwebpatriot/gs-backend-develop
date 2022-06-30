<?php
/**
 * Created by PhpStorm.
 * User: nazarian
 * Date: 3/18/19
 * Time: 2:56 PM
 */

namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PaysystemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'description' => $this->description,
            'logo' => $this->logo
                ? Storage::disk(config('filesystems.cloud'))->url($this->logo)
                : null
        ];
    }
}