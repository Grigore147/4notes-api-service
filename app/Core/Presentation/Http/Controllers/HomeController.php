<?php

declare(strict_types=1);

namespace App\Core\Presentation\Http\Controllers;

use Illuminate\Http\Request;

final class HomeController extends Controller
{
    public function index(Request $request)
    {
        return view('welcome');
    }
}
