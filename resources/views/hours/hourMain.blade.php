@extends('layouts.master')

@section('mainTabs')
    @include('mainTabs')
@endsection

@section('message')
    @include('hours.message')
@endsection

@section('searchBox')
    @include('hours.searchBox')
@endsection

@section('contents01')
    @include('hours.contents01')
@endsection

@include('hours.modal')
