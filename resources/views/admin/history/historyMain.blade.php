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
    @include('admin.history.message')
@endsection

@section('searchBox')
    @include('admin.history.searchBox')
@endsection

@section('contents01')
    @include('admin.history.contents01')
@endsection

@include('admin.history.modal')
