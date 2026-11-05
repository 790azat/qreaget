@extends('layouts.site')
@section('title', __('site.account.new_request'))
@section('content')
@include('account._nav')
<div class="container" style="padding-bottom:96px;max-width:860px">@include('partials.request-form', ['departments' => $departments])</div>
@endsection
