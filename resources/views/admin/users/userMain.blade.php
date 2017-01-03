@extends('layouts.master')


@section('mainTabs')
    @include('mainTabs')
@endsection

@section('subTabs')
    @include('admin.subTabs')
@endsection

@section('message')
    @include('admin.users.message')
@endsection

@section('searchBox')
    @include('admin.users.searchBox')
@endsection
{{--
@section('contents01')
    @include('admin.users.contents01')
@endsection

{{--@include('workingHours.modal')--}}
