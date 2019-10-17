@extends('layouts.app')

@section('title')
    {{ $client->name }}
@endsection

@section('content')
<div id="app" class="main container-fluid">
    <div class="row row-stretch">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header bg-info text-light">
                            <h2> {{ $client->name }} Subscribers </h2>
                        </div>
                        <div class="card-body">
                                <button type="button" class="btn btn-success mb-2" onclick="window.location.href='/import-subscriber/{{ $client->slug }}/{{$client->client_uuid}}'">
                                <span class="fa fa-plus"></span>
                                Import
                            </button>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Lastname</th>
                                        <th>Firstname</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($subscribers->isEmpty())
                                        <tr>
                                            <td class="text-center" colspan="3">
                                                <div class="mt-4">
                                                    Empty. No subscriber(s) found.
                                                        <a href="/import-subscriber/{{ $client->slug }}/{{ $client->client_uuid }}" style="text-decoration:none;">
                                                        <span class="text-primary"> Import ?</span>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @else
                                        @foreach($subscribers as $subscriber)
                                            <tr>
                                                <td>{{ $subscriber->lastname }}</td>
                                                <td>{{ $subscriber->firstname }}</td>
                                                <td><a href="/{{$client->slug}}/api/stats/email/{{$subscriber->email}}" style="text-decoration: none;">{{ $subscriber->email }}</a></td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{!! Html::script(mix('js/app.js')) !!}
@endsection