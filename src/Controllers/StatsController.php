<?php

namespace ArchintelDev\SesCompanion\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ArchintelDev\SesCompanion\Models\Client;

class StatsController extends Controller
{
    public function hasBounced($slug, $email)
    {
        return Client::hasBounced($slug, $email);
    }

    public function hasComplained($slug, $email)
    {
        return Client::hasComplained($slug, $email);
    }

    public function statsForBatch($slug, $batchName)
    {
        return ['success' => true, 'data' => Client::statsForBatch($slug, $batchName)];
    }

    public function statsForEmail($slug, $group, $email)
    {
        return ['success' => true, 'data' => Client::statsForEmail($slug, $group, $email)];
    }
}
