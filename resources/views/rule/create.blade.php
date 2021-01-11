@extends('layouts.app')

@section('content')
<div class="container">

    <form method="POST" action="/rule">
        @csrf()
        @method('post')
        <div class="form-group">
            <label for="ruleName">Rule Name</label>
            <input name="name" id="ruleName" class="form-control" type="text" placeholder="Rule Name" required value="{{old('name')}}">
        </div>

        @error('name')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="form-group">
            <label for="scoreFrom">From</label>
            <input name="from" id="scoreFrom" class="form-control" type="text" placeholder="From Score" required value="{{old('from')}}">
        </div>

        @error('from')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="form-group">
            <label for="scoreTo">To</label>
            <input name="to" id="scoreTo" class="form-control" type="text" placeholder="To Score" required value="{{old('to')}}">
        </div>

        @error('to')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <button type="submit" class="btn btn-secondary">Submit</button>
    </form>
    @endsection
</div>
