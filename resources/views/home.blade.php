@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
					<fieldset class="row">
					<legend>фермочка для овечек</legend>
					@php
						$n = 0;
					@endphp
                    @foreach($paddocks as $paddock)
						<fieldset class="col-6">
						<legend>загон {{ $loop->iteration }}</legend>
						<select id="p{{ $loop->iteration }}" size="10" style="width:100%">
						@foreach($paddock as $sheep)
							@php
								$n++;
							@endphp
							<option id="s{{ $n }}">{{ $sheep }}</option>
						@endforeach
						</select>
						</fieldset>
					@endforeach
					</fieldset>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
