<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function security()
    {
        return Inertia::render('Settings/Security/Edit');
    }

    public function advanced()
    {
        return Inertia::render('Settings/Advanced/DeleteUserForm');
    }
}
