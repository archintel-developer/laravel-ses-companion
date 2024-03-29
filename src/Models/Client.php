<?php

namespace ArchintelDev\SesCompanion\Models;

use ArchintelDev\LaravelSes\Models\SentEmail;
use ArchintelDev\LaravelSes\Models\EmailLink;
use ArchintelDev\LaravelSes\Models\EmailBounce;
use ArchintelDev\LaravelSes\Models\EmailComplaint;
use ArchintelDev\LaravelSes\Models\EmailOpen;
use ArchintelDev\SesCompanion\Models\Group;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';

    protected $guarded = [];
    
    protected $fillable = [
        'email', 'name', 'slug', 'client_uuid'
    ];

    public function sentEmails()
    {
        return $this->hasMany('ArchintelDev\LaravelSes\Models\SentEmail', 'client_id', 'client_uuid');
    }

    public function emailOpens()
    {
        return $this->hasMany('ArchintelDev\LaravelSes\Models\EmailOpen', 'client_id', 'client_uuid');
    }

    public function emailComplaints()
    {
        return $this->hasMany('ArchintelDev\LaravelSes\Models\EmailComplaint', 'client_id', 'client_uuid');
    }

    public function emailBounces()
    {
        return $this->hasMany('ArchintelDev\LaravelSes\Models\EmailBounce', 'client_id', 'client_uuid');
    }

    public static function hasBounced($slug, $group, $email)
    {
        $emailBounces =  EmailBounce::whereEmail($email)->get();

        if ($emailBounces->isEmpty()) {
            return response()->json([
                'success' => true,
                'bounced' => false
            ]);
        }

        return response()->json([
            'success' => true,
            'bounced' => true,
            'bounces' => $emailBounces
        ]);
    }

    public static function hasComplained($slug, $group, $email)
    {
        $id = self::whereSlug($slug)->pluck('client_uuid')->first();
        if($id == null) {
            return response()->json([
                'success' => true,
                'client'    => false,
                'complained' => false
            ]);
        }
        
        $emailComplaints =  EmailComplaint::whereEmail($email)->where('client_id', $id)->get();

        if ($emailComplaints->isEmpty()) {
            return response()->json([
                'success' => true,
                'complained' => false
            ]);
        }

        return response()->json([
            'success' => true,
            'complained' => true,
            'client' => Client::whereSlug($slug)->get(),
            'complaints' => $emailComplaints
        ]);
    }

    public static function statsForEmail($slug, $group, $email)
    {
        $account = Client::whereSlug($slug)->first();
        if($account == null) {
            return response()->json([
                'success' => true,
                'data'    => false
            ]);
        }


        return [
            'account' => self::whereSlug($slug)->first(),
            'group' => Group::whereSlug($group)->get(),
            'counts' => [
                'sent_emails' => SentEmail::whereEmail($email)->where('client_id', $account->client_uuid)->where('batch', $group)->count(),
                'deliveries' => SentEmail::whereEmail($email)->where('client_id', $account->client_uuid)->where('batch', $group)->whereNotNull('delivered_at')->count(),
                'opens' => EmailOpen::whereEmail($email)->where('batch', $group)->whereNotNull('opened_at')->count(),
                'bounces' => EmailBounce::whereEmail($email)->whereNotNull('bounced_at')->count(),
                'complaints' => EmailComplaint::whereEmail($email)->whereNotNull('complained_at')->count(),
                'click_throughs' => EmailLink::join(
                        'laravel_ses_sent_emails',
                        'laravel_ses_sent_emails.id',
                        'laravel_ses_email_links.sent_email_id'
                    )
                    ->where('laravel_ses_sent_emails.email', '=', $email)
                    ->whereClicked(true)
                    ->where('laravel_ses_sent_emails.batch', $group)
                    ->count(\DB::raw('DISTINCT(laravel_ses_sent_emails.id)')) // if a user clicks two different links on one campaign, only one is counted
            ],
            'data' => [
                'sent_emails' => SentEmail::whereEmail($email)->where('client_id', $account->client_uuid)->where('batch', $group)->get(),
                'deliveries' => SentEmail::whereEmail($email)->where('client_id', $account->client_uuid)->where('batch', $group)->whereNotNull('delivered_at')->get(),
                'opens' => EmailOpen::whereEmail($email)->where('batch', $group)->whereNotNull('opened_at')->get(),
                'bounces' => EmailBounce::whereEmail($email)->whereNotNull('bounced_at')->get(),
                'complaints' => EmailComplaint::whereEmail($email)->whereNotNull('complained_at')->get(),
                'click_throughs' => EmailLink::join(
                    'laravel_ses_sent_emails',
                    'laravel_ses_sent_emails.id',
                    'laravel_ses_email_links.sent_email_id'
                )
                ->where('laravel_ses_sent_emails.email', '=', $email)
                ->where('laravel_ses_sent_emails.batch', $group)
                ->whereClicked(true)
                ->get()
            ]
        ];
    }

    public static function statsForBatch($slug, $group, $batchName)
    {
        $id = self::whereSlug($slug)->pluck('client_uuid')->first();
        if($id == null) {
            return response()->json([
                'success' => true,
                'data'    => false
            ]);
        }

        return [
            'client' => self::where('client_uuid', $id)->whereSlug($slug)->get(),
            'group' => Group::whereSlug($batchName)->get(),
            'send_count' => SentEmail::whereBatch($batchName)->where('client_id', $id)->count(),
            'deliveries' => SentEmail::whereBatch($batchName)->where('client_id', $id)->whereNotNull('delivered_at')->count(),
            'opens' => SentEmail::join(
                                    'laravel_ses_email_opens',
                                    'laravel_ses_sent_emails.id',
                                    'laravel_ses_email_opens.sent_email_id'
                                )
                                ->where('laravel_ses_sent_emails.batch', $batchName)
                                ->where('laravel_ses_sent_emails.client_id', $id)
                                ->whereNotNull('laravel_ses_email_opens.opened_at')
                                ->count(),
            'bounces' => SentEmail::join(
                                    'laravel_ses_email_bounces',
                                    'laravel_ses_sent_emails.id',
                                    'laravel_ses_email_bounces.sent_email_id'
                                )
                                ->where('laravel_ses_sent_emails.batch', $batchName)
                                ->where('laravel_ses_sent_emails.client_id', $id)
                                ->whereNotNull('laravel_ses_email_bounces.bounced_at')
                                ->count(),
            'complaints' => SentEmail::join(
                                    'laravel_ses_email_complaints',
                                    'laravel_ses_sent_emails.id',
                                    'laravel_ses_email_complaints.sent_email_id'
                                )
                                ->where('laravel_ses_sent_emails.batch', $batchName)
                                ->where('laravel_ses_sent_emails.client_id', $id)
                                ->whereNotNull('laravel_ses_email_complaints.complained_at')
                                ->count(),
            'click_throughs' => SentEmail::where('laravel_ses_sent_emails.batch', $batchName)
                                ->where('laravel_ses_sent_emails.client_id', $id)
                                ->join('laravel_ses_email_links', function ($join) {
                                    $join
                                        ->on('laravel_ses_sent_emails.id', '=', 'sent_email_id')
                                        ->where('laravel_ses_email_links.clicked', '=', true);
                                })
                                ->select('email')
                                ->count(\DB::raw('DISTINCT(email)')),
            'link_popularity' => SentEmail::where('laravel_ses_sent_emails.batch', $batchName)
                                ->where('laravel_ses_sent_emails.client_id', $id)
                                ->join('laravel_ses_email_links', function ($join) {
                                    $join
                                        ->on('laravel_ses_sent_emails.id', '=', 'sent_email_id')
                                        ->where('laravel_ses_email_links.clicked', '=', true);
                                })
                                ->get()
                                ->groupBy('original_url')
                                ->map(function ($linkClicks) {
                                    return ['clicks' => $linkClicks->count()];
                                })
                                ->sortByDesc('clicks')
        ];
    }
}