<?php $message = Session::get('message') ?>

@if(Session::has('success'))
<div class="alert alert-success alert-dismissible" role="alert">
    <span class="alert-icon"><i class="fa fa-remove"></i></span>
    <span class="alert-text"><strong> Error</strong> {{$message}}</span>
        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">x</span>
        </button>
  </div>
@endif

@if(Session::has('error'))
<div class="alert alert-danger alert-dismissible" role="alert">
    <span class="alert-icon"><i class="fa fa-remove"></i></span>
    <span class="alert-text"><strong> Error</strong> {{$message}}</span>
        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">x</span>
        </button>
  </div>
@endif

@if(Session::has('warning'))
<div class="alert alert-warning alert-dismissible" role="alert">
    <span class="alert-icon"><i class="fa fa-warning"></i></span>
    <span class="alert-text"><strong> Error</strong> {{$message}}</span>
        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">x</span>
        </button>
  </div>
@endif

@if(Session::has('info'))
<div class="alert alert-info alert-dismissible" role="alert">
    <span class="alert-icon"><i class="fa fa-info"></i></span>
    <span class="alert-text"><strong> Error</strong> {{$message}}</span>
        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">x</span>
        </button>
  </div>
@endif
