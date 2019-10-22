<?php

namespace ArchintelDev\SesCompanion\Controllers;

use Storage;
use Illuminate\Routing\Controller;
use ArchintelDev\SesCompanion\Imports\SubscribersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function import_subscriber($uuid)
    {
        return view('companion.import-subscriber');
    }

    public function importSubscriber(Request $request)
    {
        if($request->hasFile('file')) {
            $contents = file_get_contents($request->file('file'));
            $fileExtension = $request->file->getClientOriginalExtension();
            $name = $request->account.'_'.$request->group.'.'.$fileExtension;
            
            Storage::disk('public')->put($name, $contents);

            $path = 'storage/' . $name;

            if($fileExtension == 'xlsx') {
                $saved = Excel::import(new SubscribersImport($request->account_id, $request->group), $path);
                if($saved) {
                    return response()->json(['status' => true, 'msg' => 'Imported successfuly!']);
                }
            }
        }
    }

}
