<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\AdminRequest;

class PermissionRequest extends AdminRequest
{
    public function rules(): array
    {
        $permissionId = $this->route('permission');

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:permissions,slug,' . $permissionId],
            'module' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }
}
