@extends('layouts.master')

@section('currentUserName')
    {{$currentUserInfo['firstNm']}} {{$currentUserInfo['lastNm']}}
@endsection

@section('mainTabs')
    @include('mainTabs')
@endsection

@section('message')
    @include('history03.message')
@endsection

@section('searchBox')
    @include('history03.searchBox')
@endsection

@section('contents01')
    @include('history03.contents01')
@endsection

@include('history03.modal')

