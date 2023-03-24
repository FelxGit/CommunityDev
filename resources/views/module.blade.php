@extends('layouts.app')
@include('assets.tailwind')

{{-- init stacks --}}
@include('assets.trumbowyg')

@section('content')
    <div id="app">
        <app></app>
    </div>
@endsection

