<?php

namespace ArchintelDev\SesCompanion\Controllers;

use Illuminate\Http\Request;
use ArchintelDev\SesCompanion\Models\Client;

class StatsController extends Controller
{
    public function hasBounced($slug, $email)
    {
        return \App\Client::hasBounced($slug, $email);
    }

    public function hasComplained($slug, $email)
    {
        return \App\Client::hasComplained($slug, $email);
    }

    public function statsForBatch($slug, $batchName)
    {
        return ['success' => true, 'data' => \App\Client::statsForBatch($slug, $batchName)];
    }

    public function statsForEmail($slug, $email)
    {
        return ['success' => true, 'data' => \App\Client::statsForEmail($slug, $email)];
    }
}
