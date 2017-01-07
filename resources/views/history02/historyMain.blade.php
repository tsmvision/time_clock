@extends('layouts.master')

{{--
@section('currentUserName')
    {{$currentUserInfo['firstNm']}} {{$currentUserInfo['lastNm']}}
@endsection
--}}

@section('mainTabs')
    @include('mainTabs')
@endsection

@section('message')
    @include('history02.message')
@endsection

@section('searchBox')
    @include('history02.searchBox')
@endsection
--}}

@section('contents01')
    @include('history02.contents01')
@endsection

{{--
@include('history.modal')
--}}
