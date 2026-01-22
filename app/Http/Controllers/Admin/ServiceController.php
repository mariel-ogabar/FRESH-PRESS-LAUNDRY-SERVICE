<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MainService;
use App\Models\AddOn;
use App\Http\Requests\Admin\StoreServiceRequest;
use App\Http\Requests\Admin\StoreAddonRequest;  
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display the public-facing service list for customers.
     */
    public function publicIndex()
    {
        $services = \App\Models\MainService::where('is_active', true)->get();
        $addons = \App\Models\AddOn::where('is_active', true)->get();
        
        return view('services.index', compact('services', 'addons'));
    }

    /**
     * Display the management list for Admin/Staff.
     */
    public function index()
    {
        $services = \App\Models\MainService::all();
        $addons = \App\Models\AddOn::all();
        
        return view('admin.services.index', compact('services', 'addons'));
    }

    /**
     * Store Main Service securely
     */
    public function storeService(StoreServiceRequest $request)
    {
        MainService::create($request->validated());

        return back()->with('success', 'New service added to the menu.');
    }

    /**
     * Store Add-on securely
     */
    public function storeAddon(StoreAddonRequest $request)
    {
        AddOn::create($request->validated());

        return back()->with('success', 'New add-on added.');
    }

    public function toggleService($id)
    {
        $service = MainService::findOrFail($id);
        $service->update(['is_active' => !$service->is_active]);

        return back()->with('success', 'Service status updated.');
    }

    /**
     * Toggle Add-on status (Active/Inactive)
     */
    public function toggleAddon($id)
    {
        $addon = AddOn::findOrFail($id);
        
        $addon->update([
            'is_active' => !$addon->is_active
        ]);

        return back()->with('success', 'Add-on availability updated.');
    }

    public function destroyService($id)
    {
        MainService::findOrFail($id)->delete(); 
        return back()->with('success', 'Service archived.');
    }

    public function restoreService($id)
    {
        MainService::withTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'Service restored.');
    }

    /**
     * Update Service securely
     */
    public function updateService(StoreServiceRequest $request, $id)
    {
        $service = MainService::findOrFail($id);
        $service->update($request->validated());

        return back()->with('success', 'Service updated successfully.');
    }

    /**
     * Update Add-on securely
     */
    public function updateAddon(StoreAddonRequest $request, $id)
    {
        $addon = AddOn::findOrFail($id);
        $addon->update($request->validated());

        return back()->with('success', 'Add-on updated successfully.');
    }

    public function destroyAddon($id)
    {
        $addon = AddOn::findOrFail($id);
        $addon->delete(); 

        return back()->with('success', 'Add-on has been removed.');
    }
}