<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function updateCommission(Request $request)
    {
        $request->validate([
            'commission' => 'required|numeric|min:0|max:100'
        ]);

        Setting::updateOrCreate(
            ['key' => 'admin_commission_percentage'],
            ['value' => $request->commission]
        );

        return back()->with('success', 'Commission updated successfully');
    }

    public function editCommission()
{
    $commission = \App\Models\Setting::get(
        'admin_commission_percentage',
        20
    );

    return view('admin.settings.commission', compact('commission'));
}

}
