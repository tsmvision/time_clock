@extends('layouts.master')



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