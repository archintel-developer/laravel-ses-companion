<?php

namespace ArchintelDev\SesCompanion\Http\Controllers;

use Storage;
use ArchintelDev\SesCompanion\Imports\SubscribersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function import_subscriber($uuid)
    {
        return view('companion.import_subscriber');
    }

    public function importSubscriber(Request $request)
    {
        if($request->hasFile('file')) {
            $contents = file_get_contents($request->file('file'));
            $fileExtension = $request->file->getClientOriginalExtension();
            $name = $request->slug.'.'.$fileExtension;
            
            Storage::disk('public')->put($name, $contents);

            $path = 'storage/' . $name;

            if($fileExtension == 'xlsx') {
                Excel::import(new SubscribersImport($request->client_id), $path);
            }
        }
    }

}
