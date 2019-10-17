<?php

namespace ArchintelDev\SesCompanion\Imports;

use ArchintelDev\SesCompanion\Subscriber;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SubscribersImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function __construct($client_id)
    {
        $this->client_id = $client_id;
    }

    public function collection(Collection $collection)
    {
        foreach($collection as $row) {
            Subscriber::create([
                'firstname' => $row['firstname'],
                'lastname'  => $row['lastname'],
                'email'     => $row['email'],
                'client_id' => $this->client_id
            ]);
        }
    }
}
