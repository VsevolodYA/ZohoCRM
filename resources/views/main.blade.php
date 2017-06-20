@extends('layouts.app')
@section('content')
    {{--Main page--}}
    <div style="text-align: center" class="col-md-6 col-md-offset-3">
        <h3>ZohoCRM Test</h3>
        <a style="text-decoration: none;" href="{{ action('v1\MainController@getContacts') }}">
            <button type="button" class="btn">Contacts v1</button>
        </a>
        <a style="text-decoration: none;" href="{{ action('v2\MainController@getContacts') }}">
            <button type="button" class="btn">Contacts v2</button>
        </a>
    </div>
@endsection