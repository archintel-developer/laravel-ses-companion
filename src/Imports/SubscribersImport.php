<?php

namespace ArchintelDev\SesCompanion\Imports;

use ArchintelDev\SesCompanion\Models\Subscriber;
use ArchintelDev\SesCompanion\Models\Group;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SubscribersImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function __construct($client_id, $slug)
    {
        $this->client_id = $client_id;
        $this->slug = $slug;
    }

    public function collection(Collection $collection)
    {
        $group = Group::where('slug', $this->slug)->first();
        foreach($collection as $row) {
            Subscriber::create([
                'firstname' => $row['firstname'],
                'lastname'  => $row['lastname'],
                'email'     => $row['email'],
                'client_id' => $this->client_id,
                'group_id' => $group->id
            ]);
        }
    }
}
