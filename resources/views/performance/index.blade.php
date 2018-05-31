@extends('layouts.dashboard')

@section('template_title')
    Performance Comercial
@endsection

@section('template_fastload_css')
@endsection

@section('header')
    Performance Comercial
@endsection
<?php 

#dd($consultores); 

?>
<link rel="stylesheet" type="text/css" href="{{ url('/css/table_custom.css') }}">
<script src="{{ url('js/jquery.min.js') }}"></script>
<script src="{{ url('js/chartjs/Chart.bundle.min.js') }}"></script>
<script src="{{ url('js/gchartloader.js') }}"></script>


{!! csrf_field() !!}
@section('breadcrumbs')

    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <a itemprop="item" href="{{url('/')}}">
            <span itemprop="name">
                {{ trans('titles.app') }}
            </span>
        </a>
        <i class="material-icons">chevron_right</i>
        <meta itemprop="position" content="1" />
    </li>
    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="active">
        <a itemprop="item" href="" class="">
            <span itemprop="name">
                Estadisticas
            </span>
        </a>
        <meta itemprop="position" content="2" />
    </li>

@endsection

@section('content')

@include('performance.partials.selector')

@endsection

