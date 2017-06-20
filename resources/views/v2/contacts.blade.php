@extends('layouts.app')
@section('content')
    {{-- Table with all contact in database --}}
    <h1>Contacts</h1>
    <div>
        <a style="text-decoration: none;" href="{{ action('v2\MainController@getZohoContacts') }}">
            <button type="button" class="btn">Update contacts</button>
        </a>
        <a style="text-decoration: none;" href="{{ action('v2\MainController@getInsertContacts') }}">
            <button type="button" class="btn">Insert contact</button>
        </a>
    </div>
    <br>
    <table class="table table-condensed">
        @foreach($contacts as $contact)
            <thead>
            <tr>
                <th>#</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{ $contact->CONTACT_ID }}</td>
                <td>{{ $contact->first_name }}</td>
                <td>{{ $contact->last_name }}</td>
                <td>{{ $contact->email }}</td>
                <td>{{ $contact->phone }}</td>
            </tr>
            </tbody>
        @endforeach
    </table>
@endsection