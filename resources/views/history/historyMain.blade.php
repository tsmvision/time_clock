@extends('layouts.master')

@section('mainTabs')
    @include('mainTabs')
@endsection

@section('searchBox')
    @include('history.searchBox')
@endsection

@section('contents01')
    @include('history.contents01')
@endsection

@include('history.modal')
