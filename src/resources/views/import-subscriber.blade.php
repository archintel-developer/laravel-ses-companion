@extends('layouts.app')

@section('title')
Import Subscriber
@endsection

@section('content')
<div id="app" class="main container-fluid">
    {{-- <import-subscriber></import-subscriber> --}}
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    {{-- <div class="card-header"> --}}
                        {{-- <a href="{{ Request::referrer() }}" class="" style="text-decoration:none;">&xlarr; Back</a> --}}
                    {{-- </div> --}}

                        <form method="post" action="{{ route('importSub', $uuid ?? '') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">

                            @if (session('status') == 'Failed')
                                <div class="alert alert-danger" role="alert">
                                    {{ session('msg') }}
                                </div>
                            @endif
                            @if (session('status') == 'Success')
                                @if (session('msg'))
                                    <div class="alert alert-success" role="alert">
                                        {{ session('msg') }}
                                    </div>
                                @endif
                            @endif
                            <div class="form-group">
                                <h3 for="file">Upload Users</h3>
                                <input type="file" name="file" id="file">

                                <input type="hidden" name="account" id="account" value="{{Request::segment(2)}}">
                                <input type="hidden" name="account_id" id="account_id" value="{{Request::segment(3)}}">
                                <input type="hidden" name="group" id="group" value="{{Request::segment(4)}}">
                            </div>
                            <div class="form-group" id="btnBtn">
                                <button type="submit" id="submit" class="btn btn-primary">Upload Users</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{!! Html::script(mix('js/app.js')) !!}
@endsection