<?php

namespace ArchintelDev\SesCompanion\Controllers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ArchintelDev\LaravelSes\SesMail;
use ArchintelDev\SesCompanion\Mail\SendMail;
use ArchintelDev\SesCompanion\Models\Group;
use Psr\Http\Message\ServerRequestInterface;
use ArchintelDev\LaravelSes\Models\EmailOpen;
use ArchintelDev\LaravelSes\Models\EmailLink;
use ArchintelDev\LaravelSes\Models\SentEmail;
use ArchintelDev\LaravelSes\Models\EmailBounce;
use ArchintelDev\SesCompanion\Models\Subscriber;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SnsController extends BaseController
{
    
    public function open($beaconIdentifier)
    {
        try {
            $open = EmailOpen::whereBeaconIdentifier($beaconIdentifier)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'errors' => ['Invalid Beacon']], 422);
        }

        $open->opened_at = Carbon::now();
        $open->save();

        return redirect(config('app.url')."/laravel-ses/to.png");
    }

    public function click($linkIdentifier)
    {
        $link = EmailLink::whereLinkIdentifier($linkIdentifier)->firstOrFail();
        $link->setClicked(true)->incrementClickCount();
        return redirect($link->original_url);
    }

    public function bounce(ServerRequestInterface $request)
    {
        $this->validateSns($request);

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
                'bounced_at' => Carbon::parse($message->mail->timestamp)
            ]);
        } catch (ModelNotFoundException $e) {
            //bounce won't be logged if this is hit
        }
    }

    public function complaint(ServerRequestInterface $request)
    {
        $this->validateSns($request);

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
                'complained_at' =>  Carbon::parse($message->mail->timestamp)
            ]);
        } catch (ModelNotFoundException $e) {
        }
    }

    public function delivery(ServerRequestInterface $request)
    {
        $this->validateSns($request);

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
        } catch (ModelNotFoundException $e) {
            //delivery won't be logged if this hits
        }
    }

    public function send($type, $account, $id, $group=null)
    {
        // $emails = [
        //     'ruel', 'seth.abaquita', 'efren', 'aram', 'arnold', 'nino.espina', 'claire.dolorican', 'jancarl', 'zach.hangad', 'eric.bustamante'
        // ];
        if($group != null)
            $group_id = Group::where('slug', $group)->first()->id;
        if($type == 'account')
            $emails = Subscriber::where('client_id', $id)->get();
        if($type == 'group')
            $emails = Subscriber::where('client_id', $id)->where('group_id', $group_id)->get();

        $datas = array();
        foreach($emails as $email)
            array_push($datas, $email->email);
        
        if(count($datas) == 0)
            return response()->json(['success' => false, 'msg' => 'No subscribers found.']);
            
        foreach($datas as $email) {
            if($group != null) {
                SesMail::enableAllTracking()->setAccount($id)->setBatch($group)->to($email)->send(new SendMail('Si Seth Joey Hinampas Abaquita kay gwapo.'));
            } else {
                SesMail::enableAllTracking()->setAccount($id)->to($email)->send(new SendMail('Si Seth Joey Hinampas Abaquita kay gwapo.'));
            }
        }

        return response()->json(['success' => true, 'msg' => 'Sample email sent.']);
    }
}
