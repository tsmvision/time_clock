@extends('layouts.master')

@section('currentUserName')
    {$currentUserName['firstNm']}} {{$currentUserName['lastNm']}}
@endsection

@section('mainTabs')
    @include('mainTabs')
@endsection

@section('message')
    @include('history.message')
@endsection

@section('searchBox')
    @include('history.searchBox')
@endsection

@section('contents01')
    @include('history.contents01')
@endsection

@include('history.modal')

