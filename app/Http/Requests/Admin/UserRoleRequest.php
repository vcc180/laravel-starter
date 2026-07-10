<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\AdminRequest;

class UserRoleRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'roles' => ['array'],
            'roles.*' => ['exists:roles,id'],
        ];
    }
}
