<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UnknownUid;
use Illuminate\Http\Request;

class UnknownUidController extends Controller
{
    public function index()
    {
        $unknownUids = UnknownUid::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.unknown_uids.index', compact('unknownUids'));
    }

    public function destroy($id)
    {
        try {
            $unknownUid = UnknownUid::findOrFail($id);
            $uuid = $unknownUid->uuid;
            $unknownUid->delete();
            
            return redirect()->route('admin.unknown_uids.index')
                ->with('success', "UID '{$uuid}' berhasil dihapus dari daftar tidak terdaftar.");
        } catch (\Exception $e) {
            return redirect()->route('admin.unknown_uids.index')
                ->with('error', 'Gagal menghapus UID. Silakan coba lagi.');
        }
    }
}
