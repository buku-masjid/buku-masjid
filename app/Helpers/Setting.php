<?php

namespace App\Helpers;

use App\Models\Setting as SettingModel;
use Illuminate\Database\Eloquent\Model;

class Setting
{
    protected $settings;

    protected $modelId = null;
    protected $modelType = null;

    public function __construct()
    {
        $this->settings = SettingModel::all();
    }

    public function get(string $key, $default = ''): ?string
    {
        $setting = $this->settings->filter(function ($item) use ($key) {
            if ($this->modelId && $this->modelType) {
                return $item->model_id == $this->modelId && $item->model_type == $this->modelType && $item->key == $key;
            }

            return is_null($item->model_id) && is_null($item->model_type) && $item->key == $key;
        })->first();
        $this->clearModel();

        if ($setting) {
            return $setting->value;
        }

        return $default;
    }

    public function set(string $key, ?string $value): ?string
    {
        $setting = $this->settings->filter(function ($item) use ($key) {
            if ($this->modelId && $this->modelType) {
                return $item->model_id == $this->modelId && $item->model_type == $this->modelType && $item->key == $key;
            }

            return is_null($item->model_id) && is_null($item->model_type) && $item->key == $key;
        })->first();

        if ($setting) {
            $setting->value = $value;
            $setting->save();
        } else {
            $setting = new SettingModel;
            $setting->model_id = $this->modelId;
            $setting->model_type = $this->modelType;
            $setting->key = $key;
            $setting->value = $value;
            $setting->save();
        }
        $this->clearModel();

        return $value;
    }

    public function for(Model $model): self
    {
        $this->modelId = $model->getKey();
        $this->modelType = $model->getMorphClass();

        return $this;
    }

    private function clearModel()
    {
        $this->modelId = null;
        $this->modelType = null;

        return $this;
    }
}
