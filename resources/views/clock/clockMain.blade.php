@extends('layouts.master')

@section('currentUserName')
    {{$currentUserName['firstNm']}} {{$currentUserName['lastNm']}}
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