<?php

namespace App\Helpers;

use App\Models\Setting as SettingModel;

class Setting
{
    protected $settings;

    protected $userId = null;

    public function __construct()
    {
        $this->settings = SettingModel::all();
    }

    public function get($key, $default = '')
    {
        $setting = $this->settings->filter(function ($item) use ($key) {
            if ($this->userId) {
                return $item->user_id == $this->userId && $item->key == $key;
            }

            return is_null($item->user_id) && $item->key == $key;
        })->first();

        if ($setting) {
            return $setting->value;
        }

        return $default;
    }

    public function set($key, string $value)
    {
        $setting = $this->settings->filter(function ($item) use ($key) {
            if ($this->userId) {
                return $item->user_id == $this->userId && $item->key == $key;
            }

            return is_null($item->user_id) && $item->key == $key;
        })->first();

        if ($setting) {
            $setting->value = $value;
            $setting->save();
        } else {
            $setting = new SettingModel();
            $setting->user_id = $this->userId;
            $setting->key = $key;
            $setting->value = $value;
            $setting->save();
        }

        return $value;
    }

    public function forUser($user)
    {
        $userModel = config('auth.providers.users.model');
        if ($user instanceof $userModel) {
            $user = $user->getKey();
        }

        $this->userId = $user;

        return $this;
    }
}
