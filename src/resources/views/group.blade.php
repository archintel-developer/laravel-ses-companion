@extends('layouts.app')

@section('content')
<div id="app" class="container">
    <group-component slug="{{ Request::segment(2) }}" client="{{ Request::segment(3) }}"></group-component>
</div>
@endsection
