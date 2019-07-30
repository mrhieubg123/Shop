@extends('layouts.master')
@section('title', 'Change Information')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-2">
        </div>
        <div class="col-md-8">
            <div class="card">

                <div class="card-body">
                    <form method="POST" action="{{ route('member.post_change_infomation') }}">
                        @csrf

                        <div class="form-group row {{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ trans('account.name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" required value="{{ (old('name'))?old('name'):$dataUser['name']}}">

                                @if($errors->has('name'))
                                    <span class="help-block">{{ $errors->first('name') }}</span>
                                @endif

                            </div>
                        </div>

                        {{-- <div class="form-group row {{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label for="phone" class="col-md-4 col-form-label text-md-right">{{ trans('account.phone') }}</label>

                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control" name="phone" required value="{{ (old('phone'))?old('phone'):$dataUser['phone']}}">

                                @if($errors->has('phone'))
                                    <span class="help-block">{{ $errors->first('phone') }}</span>
                                @endif

                            </div>
                        </div> --}}

                       <div class="form-group row {{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ trans('account.email') }}</label>

                            <div class="col-md-6">
                              {{ $dataUser['email'] }}

                            </div>
                        </div>


                       {{-- <div class="form-group row {{ $errors->has('address1') ? ' has-error' : '' }}">
                            <label for="address1" class="col-md-4 col-form-label text-md-right">{{ trans('account.address1') }}</label>

                            <div class="col-md-6">
                                <input id="address1" type="text" class="form-control" name="address1" required value="{{ (old('address1'))?old('address1'):$dataUser['address1']}}">

                                @if($errors->has('address1'))
                                    <span class="help-block">{{ $errors->first('address1') }}</span>
                                @endif

                            </div>
                        </div> --}}

                       {{-- <div class="form-group row {{ $errors->has('address2') ? ' has-error' : '' }}">
                            <label for="address2" class="col-md-4 col-form-label text-md-right">{{ trans('account.address2') }}</label>

                            <div class="col-md-6">
                                <input id="address2" type="text" class="form-control" name="address2" required value="{{ (old('address2'))?old('address2'):$dataUser['address2']}}">

                                @if($errors->has('address2'))
                                    <span class="help-block">{{ $errors->first('address2') }}</span>
                                @endif

                            </div>
                        </div> --}}

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ trans('account.update_infomation') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection