<?php

namespace ArchintelDev\SesCompanion\Controllers;

use Illuminate\Http\Request;
use ArchintelDev\SesCompanion\Models\Group;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if($request->wantsJson()) {
            return response()->json(['groups' => Group::all()]);
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
        $client = new Group();
        $client->name = $request->name;
        $client->client_id = $request->client_id;
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
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        $client = Group::find($group);
        $deleted = $client->delete();

        if($deleted) {
            return response()->json(['msg' => ' successfuly deleted!', 'success' => true]);
        } else {
            return response()->json(['msg' => 'Error deleting data!', 'success' => false]);
        }
    }
}
