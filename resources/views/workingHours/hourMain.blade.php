@extends('layouts.master')

@section('currentUserName')
    {{$currentUserInfo['firstNm']}} {{$currentUserInfo['lastNm']}}
@endsection

@section('mainTabs')
    @include('mainTabs')
@endsection

@section('message')
    @include('workingHours.message')
@endsection

@section('searchBox')
    @include('workingHours.searchBox')
@endsection

@section('contents01')
    @include('workingHours.contents01')
@endsection

{{--@include('workingHours.modal')--}}
