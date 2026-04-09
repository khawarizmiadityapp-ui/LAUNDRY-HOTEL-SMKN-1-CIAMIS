@extends('layouts.app')

@section('content')

    {{-- 1. HERO SECTION --}}
    @include('pages.home.hero')

    {{-- 2. SERVICE SECTION --}}
    @include('pages.home.services')

    {{-- 3. WORKFLOW SECTION --}}
    @include('pages.home.workflow')

    {{-- 4. TRACKING SECTION --}}
    @include('pages.home.tracking')

@endsection