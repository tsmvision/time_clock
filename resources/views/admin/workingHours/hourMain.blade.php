@extends('layouts.master')

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
