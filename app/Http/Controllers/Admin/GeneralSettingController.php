<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Http\Controllers\Controller;

class GeneralSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setting = GeneralSetting::first();
        return view('admin.setting.general-setting.index', compact('setting'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'logo'        => ['nullable', 'max:5000', 'image'],
            'footer_logo' => ['nullable', 'max:5000', 'image'],
            'favicon'     => ['nullable', 'max:5000', 'image'],
        ]);

        // Usa sempre o PRIMEIRO registro (evita duplicatas)
        $setting = GeneralSetting::first();

        $logo        = handleUpload('logo', $setting);
        $footer_logo = handleUpload('footer_logo', $setting);
        $favicon     = handleUpload('favicon', $setting);

        // UPDATE no registro existente (não cria novo)
        $setting->logo        = (!empty($logo))        ? $logo        : $setting->logo;
        $setting->footer_logo = (!empty($footer_logo)) ? $footer_logo : $setting->footer_logo;
        $setting->favicon     = (!empty($favicon))     ? $favicon     : $setting->favicon;
        $setting->save();

        toastr('Update Successfully', 'success');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
