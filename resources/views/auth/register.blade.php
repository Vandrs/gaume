@extends('layouts.site')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('site.registration.register')</div>
                <div class="panel-body">
                    <form role="form" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <div class="col-xs-12 col-md-8 col-md-offset-2">
                            <span class="form-title">@lang('site.registration.public_information')</span>
                            <span class="help-block">
                                @lang('site.registration.public_information_help')
                            </span>
                        </div>
                        <div class="col-xs-12 col-md-8 col-md-offset-2">
                            <div class="form-group{{ $errors->has('nickname') ? ' has-error' : '' }}">
                                <label for="nickname" class="control-label">@lang('site.registration.nickname')*</label>
                                <input id="nickname" type="text" class="form-control" name="nickname" required>
                                @if ($errors->has('nickname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nickname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-8 col-md-offset-2">
                            <span class="form-title">@lang('site.registration.private_information')</span>
                            <span class="help-block">
                                @lang('site.registration.private_information_help')
                            </span>
                        </div>
                        <div class="col-xs-12 col-md-8 col-md-offset-2">
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="control-label">@lang('site.registration.name')*</label>
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-4 col-md-offset-2">
                            <div class="form-group{{ $errors->has('cpf') ? ' has-error' : '' }}">
                                <label for="name" class="control-label">@lang('site.registration.cpf')*</label>
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('cpf') }}" required autofocus>
                                @if ($errors->has('cpf'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cpf') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4 ">
                            <div class="form-group{{ $errors->has('birth_date') ? ' has-error' : '' }}">
                                <label for="name" class="control-label">@lang('site.registration.birth_date')*</label>
                                <div class="input-group">
                                    <input id="name" type="text" class="form-control" name="name" value="{{ old('birth_date') }}" required autofocus>
                                    <span class="input-group-addon">
                                        <i class="glyphicon glyphicon-calendar"></i>
                                    </span>
                                </div>
                                @if ($errors->has('birth_date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('birth_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                            
                        <div class="col-xs-12 col-md-8 col-md-offset-2">
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="control-label">@lang('site.registration.email')*</label>
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4 col-md-offset-2">
                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="control-label">@lang('site.registration.password')*</label>
                                <input id="password" type="password" class="form-control" name="password" required>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label for="password-confirm" class="control-label">@lang('site.registration.password_confirm')*</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-4 col-md-offset-2">
                            <div class="form-group{{$errors->has('state') ? ' has-error' : '' }}">
                                <label for="state" class="control-label">@lang('site.registration.state')*</label>
                                <select id="state" type="state" class="form-control" name="state" required>
                                    <option value="">@lang('site.buttons.select')</option>
                                    @foreach($states as $state)
                                    <option value="{{$state->uf}}">{{$state->name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('state'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('state') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group{{$errors->has('city') ? ' has-error' : '' }}">
                                <label for="city_name" class="control-label">@lang('site.registration.city')*</label>
                                <input id="city_name" type="text" name="city_name" value="{{old('city_name')}}" class="form-control" autocomplete="off">
                                <input id="city" type="hidden" name="city" value="{{old('city')}}" class="form-control">
                                @if ($errors->has('city'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-8 col-md-offset-2">
                            <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        @lang('site.buttons.send')
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
