<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ArchintelDev\LaravelSes\SesMail;
use App\Mail\SendMail;
use Psr\Http\Message\ServerRequestInterface;
use ArchintelDev\LaravelSes\Models\EmailOpen;
use ArchintelDev\LaravelSes\Models\EmailLink;
use ArchintelDev\LaravelSes\Models\SentEmail;
use ArchintelDev\LaravelSes\Models\EmailBounce;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SnsController extends BaseController
{
    public function send()
    {
        SesMail::enableAllTracking()->to('seth.abaquita@executivemosaic.com')->send(new SendMail('This is a sample data.'));
    }
    
    public function open($slug, $beaconIdentifier)
    {
        $id = \App\Client::whereSlug($slug)->pluck('client_uuid')->first();
        if($id == null) {
            return response()->json([
                'success' => true,
                'data'    => false
            ]);
        }

        try {
            $open = EmailOpen::whereBeaconIdentifier($beaconIdentifier)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'errors' => ['Invalid Beacon']], 422);
        }

        $open->opened_at = Carbon::now();
        $open->client_id = $id;
        $open->save();

        return redirect(config('app.url')."/laravel-ses/to.png");
    }

    public function click($slug, $linkIdentifier)
    {
        $id = \App\Client::whereSlug($slug)->pluck('client_uuid')->first();
        if($id == null) {
            return response()->json([
                'success' => true,
                'data'    => false
            ]);
        }
        $link = EmailLink::whereLinkIdentifier($linkIdentifier)->firstOrFail();
        $link->setClicked(true)->incrementClickCount();
        $link->client_id = $id;
        $link->save();
        return redirect($link->original_url);
    }

    public function bounce(ServerRequestInterface $request, $slug)
    {
        $this->validateSns($request);

        $id = \App\Client::whereSlug($slug)->pluck('client_uuid')->first();

        $result = json_decode(request()->getContent());

        //if amazon is trying to confirm the subscription
        if (isset($result->Type) && $result->Type == 'SubscriptionConfirmation') {
            $client = new Client;
            $client->get($result->SubscribeURL);

            return response()->json(['success' => true]);
        }

        $message = json_decode($result->Message);

        $messageId = $message
            ->mail
            ->commonHeaders
            ->messageId;

        $messageId  = str_replace('<', '', $messageId);
        $messageId = str_replace('>', '', $messageId);

        try {
            $sentEmail = SentEmail::whereMessageId($messageId)
                ->whereBounceTracking(true)
                ->firstOrFail();
            EmailBounce::create([
                'message_id' => $messageId,
                'sent_email_id' => $sentEmail->id,
                'type' => $message->bounce->bounceType,
                'email' => $message->mail->destination[0],
                'client_id' => $id,
                'bounced_at' => Carbon::parse($message->mail->timestamp)
            ]);
        } catch (ModelNotFoundException $e) {
            //bounce won't be logged if this is hit
        }
    }

    public function complaint(ServerRequestInterface $request, $slug)
    {
        $this->validateSns($request);

        $id = \App\Client::whereSlug($slug)->pluck('client_uuid')->first();

        $result = json_decode(request()->getContent());

        //if amazon is trying to confirm the subscription
        if (isset($result->Type) && $result->Type == 'SubscriptionConfirmation') {
            $client = new Client;
            $client->get($result->SubscribeURL);

            return response()->json(['success' => true]);
        }

        $message = json_decode($result->Message);

        $messageId = $message
            ->mail
            ->commonHeaders
            ->messageId;

        $messageId  = str_replace('<', '', $messageId);
        $messageId = str_replace('>', '', $messageId);

        try {
            $sentEmail = SentEmail::whereMessageId($messageId)
                ->whereComplaintTracking(true)
                ->firstOrFail();

            EmailComplaint::create([
                'message_id' => $messageId,
                'sent_email_id' => $sentEmail->id,
                'type' => $message->complaint->complaintFeedbackType,
                'email' => $message->mail->destination[0],
                'client_id' => $id,
                'complained_at' =>  Carbon::parse($message->mail->timestamp)
            ]);
        } catch (ModelNotFoundException $e) {
        }
    }

    public function delivery(ServerRequestInterface $request, $slug)
    {
        $this->validateSns($request);

        $id = \App\Client::whereSlug($slug)->pluck('client_uuid')->first();

        $result = json_decode(request()->getContent());

        //if amazon is trying to confirm the subscription
        if (isset($result->Type) && $result->Type == 'SubscriptionConfirmation') {
            $client = new Client;
            $client->get($result->SubscribeURL);

            return response()->json(['success' => true]);
        }

        $message = json_decode($result->Message);

        $messageId = $message
            ->mail
            ->commonHeaders
            ->messageId;

        $messageId  = str_replace('<', '', $messageId);
        $messageId = str_replace('>', '', $messageId);

        $deliveryTime =  Carbon::parse($message->delivery
            ->timestamp);

        try {
            $sentEmail = SentEmail::whereMessageId($messageId)
                ->whereDeliveryTracking(true)
                ->firstOrFail();
            $sentEmail->setDeliveredAt($deliveryTime);
            $sentEmail->client_id = $id;
            $sentEmail->save();
        } catch (ModelNotFoundException $e) {
            //delivery won't be logged if this hits
        }
    }
}
