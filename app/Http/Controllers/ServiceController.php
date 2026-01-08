<?php

namespace App\Http\Controllers;

use App\Models\MainService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = MainService::all();
        return view('services.index', compact('services'));
    }
}