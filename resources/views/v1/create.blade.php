@extends('layouts.app')
@section('content')
    {{-- Create form for insert data in ZohoCRM--}}
    <div class="col-md-6 col-md-offset-3">
        <h1>Insert contact in ZohoCRM</h1>
        <a style="text-decoration: none;" href="{{ action('v1\MainController@getContacts') }}">
            <button type="button" class="btn">View contacts</button>
        </a>
        <br>
        <form action="{{ action('v1\MainController@postInsertContacts') }}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <label for="first">First Name</label>
                <input type="text" class="form-control" required id="first" name="first" placeholder="First Name">
            </div>
            <div class="form-group">
                <label for="last">Last Name</label>
                <input type="text" class="form-control" required id="last" name="last" placeholder="Last Name">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" required id="email" name="email" placeholder="Email">
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" class="form-control" required id="phone" name="phone" placeholder="Phone">
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
        </form>
    </div>
@endsection