@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Godkendelse af din e-mail</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            Et nyt link er sendt til din e-mail.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
