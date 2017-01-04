@extends('layouts.master')

@section('currentUserName')
    {{$currentUserInfo['firstNm']}} {{$currentUserInfo['lastNm']}}
@endsection


@section('mainTabs')
    @include('mainTabs')
@endsection

@section('subTabs')
    @include('admin.subTabs')
@endsection

@section('message')
    @include('admin.workingHours.message')
@endsection

@section('searchBox')
    @include('admin.workingHours.searchBox')
@endsection

@section('contents01')
    @include('admin.workingHours.contents01')
@endsection

{{--@include('workingHours.modal')--}}
