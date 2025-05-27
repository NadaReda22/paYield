<?php

namespace App\Http\Controllers\Admin;


use App\Models\SeoSetting;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;//use Arr


class SiteSettingController extends Controller
{
    public function siteSetting()
    {
        $setting=SiteSetting::find(1);
        return view('admin/setting/setting_update',compact('setting'));
    }

    public function siteUpdate(Request $request)
{
    $setting_id = $request->id;
    
    $validatedData = $request->validate([
        'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        'support_phone' => 'nullable|string|min:10|max:20', 
        'phone_one' => 'nullable|string|min:10|max:20',
        'email' => 'nullable|email|max:191', 
        'company_address' => 'nullable|string|max:500', 
        'facebook' => 'nullable|url|max:255', 
        'twitter' => 'nullable|url|max:255',
        'youtube' => 'nullable|url|max:255',
        'copyright' => 'nullable|string|max:255',
    ]);
    
    // Get existing setting
    $SiteSetting = SiteSetting::findOrFail($setting_id);

    // Handle logo upload before updating
    if ($request->hasFile('logo')) {
        // Delete old logo if it exists
        if (!empty($SiteSetting->logo) && file_exists(public_path($SiteSetting->logo))) {
            unlink(public_path($SiteSetting->logo));
        }

        // Store new logo
        $fileName = time() . '.' . $request->logo->extension();
        $request->logo->move(public_path('uploads/site/'), $fileName);
        $validatedData['logo'] = 'uploads/site/' . $fileName;
    }

    // Update fields with validated data (including logo if new logo was uploaded)
    $SiteSetting->update($validatedData);
    
    return redirect()->back()->with('success','Site Setting updated successfully');
}



    public function seoSetting()
    {
        $seo=SeoSetting::find(1);
        return view('admin/setting/seo_update',compact('seo'));
    }



    public function seoUpdate(Request $request)
    {
        $seo_id = $request->id;

        $validatedData = $request->validate([
            'meta_title'       => 'nullable|string|max:255',
            'meta_author'      => 'nullable|string|max:255',
            'meta_keyword'     => 'nullable|string|max:500',
            'meta_description' => 'nullable|string|max:1000',
        ]);
        
       
        $seoSetting = SeoSetting::findOrFail($seo_id);
        
        // Update fields
        $seoSetting->update($validatedData);
        
        // Save changes
        $seoSetting->save();

        return redirect()->back()->with('success','Seo Setting updated successfully');    }
}
