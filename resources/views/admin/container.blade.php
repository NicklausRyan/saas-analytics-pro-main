@extends('layouts.admin')

@section('admin_content')
    @if(isset($view))
        @include($view, ['container' => true])
    @elseif(isset($__content))
        {!! $__content !!}
    @endif
@endsection
