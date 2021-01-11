@extends('layouts.app')

@section('content')
<div class="container">

    <form method="POST" action="/rule/{{$rule->id}}">
        @csrf()
        @method('PUT')
        <div class="form-group">
            <label for="ruleName">Rule  Name</label>
            <input name="name" id="ruleName" class="form-control" type="text" placeholder="Rule Name" required value="{{$rule->name}}">
        </div>

        @error('name')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="form-group">
            <label for="scoreFrom">From Score</label>
            <input name="from" id="scoreFrom" class="form-control" type="text" placeholder="From score" required value="{{$rule->from}}">
        </div>

        @error('from')
        <div class=" alert alert-danger">{{ $message }}</div>
        @enderror


        <div class="form-group">
            <label for="scoreTo">To Score</label>
            <input name="to" id="scoreTo" class="form-control" type="text" placeholder="To score" required value="{{$rule->to}}">
        </div>

        @error('to')
        <div class=" alert alert-danger">{{ $message }}</div>
        @enderror

        <button type="submit" class="btn btn-secondary">Submit</button>
    </form>
    @endsection
</div>
