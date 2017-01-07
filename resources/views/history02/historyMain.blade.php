@extends('layouts.master')

@section('currentUserName')
    {{$currentUserInfo['firstNm']}} {{$currentUserInfo['lastNm']}}
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

