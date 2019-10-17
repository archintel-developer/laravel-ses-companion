<?php

namespace ArchintelDev\SesCompanion\Controllers;

use Illuminate\Support\Str;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use ArchintelDev\SesCompanion\Models\Client;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class ManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->wantsJson()) {
            return response()->json(['clients' => Client::all()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $client = new Client();
        $client->name = $request->name;
        $client->email = $request->email;
        $client->slug = Str::slug($request->name, '-');
        $client->client_uuid = Uuid::uuid1()->toString();
        $saved = $client->save();

        if($saved) {
            return response()->json(['msg' => '<b>' . $request->name . '</b> successfuly added!', 'success' => true]);
        } else {
            return response()->json(['msg' => 'Failed to add data!', 'success' => false]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = Client::find($id);
        $deleted = $client->delete();

        if($deleted) {
            return response()->json(['msg' => ' successfuly deleted!', 'success' => true]);
        } else {
            return response()->json(['msg' => 'Error deleting data!', 'success' => false]);
        }
    }

    public function show_subscribers($uuid)
    {
        return response()->json(['id' => $uuid]);
    }

    public function show_dashboard()
    {
        return view('pages.management');
    }

    public function show_client_sub($uuid)
    {
        $clients = Client::where('client_uuid', $uuid)->first();
        $subscribers = Subscriber::where('client_id', $uuid)->get();

        return view('companion.subscriber')->with(['client' => $clients, 'subscribers' => $subscribers]);
    }
}
