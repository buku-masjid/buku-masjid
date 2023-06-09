<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = $this->resource;
        $responsData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'status' => $user->status,
        ];

        return $responsData;
    }

    public function with($request)
    {
        if ($request->route()->getName() == 'api.login') {
            return ['api_token' => $this->resource->api_token];
        }

        return [];
    }
}
