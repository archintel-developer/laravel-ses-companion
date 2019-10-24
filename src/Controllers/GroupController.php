<?php

namespace ArchintelDev\SesCompanion\Controllers;

use ArchintelDev\SesCompanion\Models\Group;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $account_id)
    {
        if($request->wantsJson()) {
            return response()->json(['groups' => Group::where('client_id', $account_id)->get()]);
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
        $group = new Group();
        $group->name = $request->name;
        $group->slug = Str::slug($request->name, '-');
        $group->client_id = $request->client_id;
        $saved = $group->save();

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
    public function destroy($id)
    {
        $client = Group::find($id);
        $deleted = $client->delete();

        if($deleted) {
            return response()->json(['msg' => ' successfuly deleted!', 'success' => true]);
        } else {
            return response()->json(['msg' => 'Error deleting data!', 'success' => false]);
        }
    }


    public function getSubscribers($account, $group_slug)
    {
        $client = Client::where('client_uuid', $account)->first();
        $group = Group::where('slug', $group_slug)->first();
        $subscribers = Subscriber::where('client_id', $account)->where('group_id', $group->id)->get();

        return response()->json(['client' => $client, 'group' => $group, 'subscribers' => $subscribers]);
    }

    public function removeSubscriber($id)
    {
        $subscriber = Subscriber::find($id);
        $deleted = $subscriber->delete();

        if($deleted) {
            return response()->json(['msg' => ' successfuly deleted!', 'success' => true]);
        } else {
            return response()->json(['msg' => 'Error deleting data!', 'success' => false]);
        }
    }

    public function get_group(Request $request)
    {
        return response()->json(['group' => Group::find($request->id)]);
    }

    public function update_group(Request $request, $id)
    {
        $group = Group::find($id);
        $group->name = $request->input('name');
        $saved = $group->save();
        if($saved) {
            return response()->json(['msg' => '<b>'.$request->name.'</b> '.' updated successfuly!', 'success' => true]);
        } else {
            return response()->json(['msg' => 'Update failed!', 'success' => false]);
        }
    }
}
