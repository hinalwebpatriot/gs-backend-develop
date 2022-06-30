<?php

namespace lenal\seo\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property integer $id
 * @property string $url
 * @property string $redirect_url
 */
class SeoRedirect extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public static function  boot()
    {
        parent::boot();

        static::saving(function(self $model) {
            $model->cleanUrls();
        });
    }

    public function cleanUrls()
    {
        $this->removeHost('url');

        if (($this->isUrl($this->redirect_url) && strpos($this->redirect_url, config('app.frontend_url')) !== false) || !$this->isUrl($this->redirect_url)) {
            $this->removeHost('redirect_url');
        }
    }

    private function removeHost($attribute)
    {
        $this->$attribute = ltrim($this->$attribute, ' /');

        $urlParts = parse_url(config('app.frontend_url'));
        $value = str_replace([
            config('app.frontend_url'),
            $urlParts['host'],
            ltrim($urlParts['host'], 'www.'),
            'https',
            'http',
            '://'
        ], '', $this->$attribute);

        $this->$attribute = ltrim($value, '/ ');
    }

    private function isUrl($value)
    {
        return filter_var($value, FILTER_VALIDATE_URL);
    }

    public function redirectUrl()
    {
        if ($this->isUrl($this->redirect_url)) {
            return $this->redirect_url;
        }

        return config('app.frontend_url') . '/' . ltrim($this->redirect_url, '/');
    }
}
