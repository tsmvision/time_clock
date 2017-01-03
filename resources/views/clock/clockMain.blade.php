@extends('layouts.master')

@section('currentUserName')
    {{$currentUserType['firstNm']}} {{$currentUserType['lastNm']}}
@endsection

@section('mainTabs')
    @include('mainTabs')
@endsection

@section('message')
    @include('clock.message')
@endsection


@section('contents01')
    @include('clock.contents01')
@endsection

@include('clock.modal')