<?php

namespace ArchintelDev\SesCompanion\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ArchintelDev\SesCompanion\Models\Client;

class StatsController extends Controller
{
    public function hasBounced($slug, $group, $email)
    {
        return Client::hasBounced($slug, $group, $email);
    }

    public function hasComplained($slug, $group, $email)
    {
        return Client::hasComplained($slug, $group, $email);
    }

    public function statsForBatch($slug, $group, $batchName)
    {
        return ['success' => true, 'data' => Client::statsForBatch($slug, $group, $batchName)];
    }

    public function statsForEmail($slug, $group, $email)
    {
        return ['success' => true, 'data' => Client::statsForEmail($slug, $group, $email)];
    }
}
