<?php

declare(strict_types=1);

namespace App\Core\Presentation\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

abstract class ApiController extends Controller // @pest-arch-ignore-line
{
    /**
     * @inheritDoc
     */
    public function authorize($ability, $arguments = [])
    {
        if (func_num_args() > 2) {
            $arguments = array_slice(func_get_args(), 1);
        } elseif (!is_array($arguments)) {
            $arguments = [$arguments];
        }

        return parent::authorize($ability, $arguments);
    }

    public function authorizeRequest(Request $request, ...$arguments)
    {
        return $this->authorize(
            Str::kebab($request->route()->getName()),
            $arguments
        );
    }
}
