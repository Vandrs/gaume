@extends('layouts.site')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-primary">
                <div class="panel-heading">@lang('site.registration.register_teacher')</div>
                <div class="panel-body">
                @if(session()->has('flash_error') && (!isset($code) || empty($code)) )
                    <div class="row">
                        @include('partials.flash-error-container')
                    </div>
                @else
                    <form role="form" method="POST" action="{{ route('teacher.registration') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <input type="hidden" name="role_id" value="{{EnumRole::STUDENT_ID}}">
                        <input type="hidden" name="code" value="{{$code}}">

                        <div class="row">
                        @include('partials.flash-error-container')
                        @if($errors->any())
                        <div class="col-xs-12 col-md-8 col-md-offset-2">
                            <div class="alert alert-danger">
                                @lang('app.messages.errors')
                            </div>
                        </div>
                        @endif
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-md-8 col-md-offset-2">
                                <span class="form-title">@lang('site.registration.public_information')</span>
                                <span class="help-block">
                                    @lang('site.registration.public_information_help')
                                </span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-md-2 col-md-offset-2 margin-bottom-10">
                                <div class="img-profile-content">
                                </div>
                                <div>
                                    <input type="file" name="photo_profile" id="photo_profile" class="hidden"/>
                                    <button id="photoSelect" class="btn btn-primary"><i class="glyphicon glyphicon-picture"></i> @lang('site.registration.profile_image')</button>
                                </div>
                                <div class="form-group{{$errors->has('image') ? ' has-error' : '' }}">
                                    @if ($errors->has('image'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('image') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group{{ $errors->has('nickname') ? ' has-error' : '' }}">
                                            <label for="nickname" class="control-label">@lang('site.registration.nickname')*</label>
                                            <input id="nickname" type="text" class="form-control" name="nickname" value="{{old('nickname')}}" >
                                            @if ($errors->has('nickname'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('nickname') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                            <label for="name" class="control-label">@lang('site.registration.name')*</label>
                                            <input id="name" type="text" class="form-control" name="name" value="{{ Util::coalesce(old('name'), $preRegistration->name) }}"  autofocus>
                                            @if ($errors->has('name'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class='row'>
                            <div class="col-xs-12 col-md-8 col-md-offset-2 margin-top-10">
                                <div class="form-group {{ $errors->has('games') ? ' has-error' : '' }}">
                                    <span class="form-title">@lang('site.registration.games')</span>
                                    <span class="help-block">
                                        @lang('site.registration.game_information')
                                    </span>
                                    @if($errors->has('games'))
                                    <span class="help-block">    
                                        @lang('site.registration.generic_game_error')
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-md-8 col-md-offset-2">
                                <div class="row">
                                    @foreach($games as $gamePlatform)
                                    <div class="col-xs-12 col-md-6 margin-top-10">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                {{$gamePlatform['game']->name}}
                                            </div>
                                            <div class="panel-body">
                                                @if($gamePlatform['game']->getPhotoUrl())
                                                <div class="row margin-bottom-10">
                                                    <div class="col-xs-12">
                                                        <img class="game-img-container" src="{{$gamePlatform['game']->getPhotoUrl()}}"  title="{{$gamePlatform['game']->name}}" alt="{{$gamePlatform['game']->name}}" />
                                                    </div>
                                                </div>
                                                @endif
                                                <div class="row">
                                                    <div class='col-xs-12'>
                                                        <label for="gameDescription_{{$gamePlatform['game']->id}}">@lang('site.registration.skill_descripion')</label>
                                                        <textarea class="form-control" id="gameDescription_{{$gamePlatform['game']->id}}" name="games[{{$gamePlatform['game']->id}}][description]" rows="5">{{old('games.'.$gamePlatform['game']->id.'.description')}}</textarea>
                                                    </div>
                                                </div>
                                                @foreach($gamePlatform['platforms'] as $platform)                                                
                                                <div class="row margin-top-10">
                                                    <div class='col-xs-12'>
                                                        <label for="gamePlatformNickname_{{$platform->id}}">@lang('site.registration.nickname'): {{$platform->name}}</label>
                                                        <input type="text" class="form-control" 
                                                               name="games[{{$gamePlatform['game']->id}}][platforms][{{$platform->id}}][nickname]" 
                                                               id="gamePlatformNickname_{{$platform->id}}"
                                                               value="{{old('games.'.$gamePlatform['game']->id.'.platforms.'.$platform->id.'.nickname')}}" />
                                                    </div>
                                                </div>    
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>


                        <div class='row'>
                            <div class="col-xs-12 col-md-8 col-md-offset-2 margin-top-10">
                                <span class="form-title">@lang('site.registration.private_information')</span>
                                <span class="help-block">
                                    @lang('site.registration.private_information_help')
                                </span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-md-4 col-md-offset-2">
                                <div class="form-group {{ $errors->has('cpf') ? ' has-error' : '' }}">
                                    <label for="cpf" class="control-label">@lang('site.registration.cpf')*</label>
                                    <input id="cpf" type="text" class="form-control" name="cpf" value="{{ old('cpf') }}"  autofocus>
                                    @if ($errors->has('cpf'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('cpf') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-4 ">
                                <div class="form-group{{ $errors->has('birth_date') ? ' has-error' : '' }}">
                                    <label for="birth_date_text" class="control-label">@lang('site.registration.birth_date')*</label>
                                    <div class="input-group">
                                        <input id="birth_date" type="hidden" name="birth_date" value="{{old('birth_date')}}">
                                        <input id="birth_date_text" type="text" class="form-control" name="birth_date_text" value="{{ old('birth_date_text') }}" readonly="">
                                        <span class="input-group-btn">
                                            <button  id="btnShowBirthDateCalendar" class="btn btn-default">
                                                <i class="glyphicon glyphicon-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
                                    @if ($errors->has('birth_date'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('birth_date') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>


                        <div class="row">    
                            <div class="col-xs-12 col-md-8 col-md-offset-2">
                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label for="email" class="control-label">@lang('site.registration.email')*</label>
                                    <input id="email" type="email" class="form-control" name="email" value="{{ Util::coalesce(old('email'), $preRegistration->email) }}" >
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-md-4 col-md-offset-2">
                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label for="password" class="control-label">@lang('site.registration.password')*</label>
                                    <input id="password" type="password" class="form-control" name="password" >
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
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" >
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-md-8 col-md-offset-2">
                                <span class="help-block">
                                    @lang('site.registration.address')
                                </span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-md-3 col-md-offset-2">
                                <div class="form-group{{$errors->has('zipcode') ? ' has-error' : '' }}">
                                    <label for="zipcode" class="control-label">@lang('site.registration.zipcode')*</label>
                                    <input type="text" name="zipcode" id="zipcode" value="{{old('zipcode')}}" class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-2">
                                <div class="form-group{{$errors->has('state') ? ' has-error' : '' }}">
                                    <label for="state" class="control-label">@lang('site.registration.state')*</label>
                                    <input type="text" name="state" id="state" value="{{old('state')}}" class="form-control" readonly="">
                                    @if ($errors->has('state'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('state') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-3">
                                <div class="form-group{{$errors->has('city') ? ' has-error' : '' }}">
                                    <label for="city" class="control-label">@lang('site.registration.city')*</label>
                                    <input id="city" type="text" name="city" value="{{old('city')}}" class="form-control" readonly="">
                                    @if ($errors->has('city'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('city') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-md-4 col-md-offset-2">
                                <div class="form-group{{$errors->has('neighborhood') ? ' has-error' : '' }}">
                                    <label for="neighborhood_name" class="control-label">@lang('site.registration.neighborhood')*</label>
                                    <input id="neighborhood" type="text" name="neighborhood" value="{{old('neighborhood')}}" class="form-control" readonly="">
                                    @if ($errors->has('neighborhood'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('neighborhood') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-4">
                                <div class="form-group{{$errors->has('street') ? ' has-error' : '' }}">
                                    <label for="street" class="control-label">@lang('site.registration.street')*</label>
                                    <input id="street" type="text" name="street" value="{{old('street')}}" class="form-control">
                                    @if ($errors->has('street'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('street') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">                        
                            <div class="col-xs-12 col-md-3 col-md-offset-2">
                                <div class="form-group{{$errors->has('number') ? ' has-error' : '' }}">
                                    <label for="number" class="control-label">@lang('site.registration.number')*</label>
                                    <input id="number" type="text" name="number" value="{{old('number')}}" class="form-control">
                                    @if ($errors->has('number'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('number') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-5">
                                <div class="form-group{{$errors->has('complement') ? ' has-error' : '' }}">
                                    <label for="complement" class="control-label">@lang('site.registration.complement')</label>
                                    <input id="complement" type="text" name="complement" value="{{old('complement')}}" class="form-control">
                                    @if ($errors->has('complement'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('complement') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row margin-top-10">
                            <div class="col-xs-12 col-md-8 col-md-offset-2">
                                <span class="form-title">@lang('site.registration.bank_info')</span>
                                <span class="help-block">
                                    @lang('site.registration.bank_info_help')
                                </span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-md-4 col-md-offset-2" >
                                <div class="form-group {{$errors->has('bank') ? ' has-error' : ''}} ">
                                    <label for="bank">@lang('site.registration.bank')*</label>
                                    <input type="text" name="bank" id="bank" class="form-control" value="{{old('bank')}}" maxlength="100">
                                    @if ($errors->has('bank'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('bank') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-4" >
                                <div class="form-group {{$errors->has('agency') ? ' has-error' : ''}} ">
                                    <label for="bank">@lang('site.registration.agency')*</label>
                                    <input type="text" name="agency" id="agency" class="form-control" value="{{old('agency')}}" maxlength="20">
                                    @if ($errors->has('agency'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('agency') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-4 col-md-offset-2">
                                <div class="form-group {{$errors->has('account') ? ' has-error' : ''}} ">
                                    <label for="account">@lang('site.registration.account')*</label>
                                    <input type="text" name="account" id="account" class="form-control" value="{{old('account')}}" maxlength="20">
                                    @if ($errors->has('account'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('account') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-2" >
                                <div class="form-group {{$errors->has('digit') ? ' has-error' : ''}} ">
                                    <label for="digit">@lang('site.registration.digit')*</label>
                                    <input type="text" name="digit" id="digit" class="form-control" value="{{old('digit')}}" maxlength="2">
                                    @if ($errors->has('digit'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('digit') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-md-8 col-md-offset-2 margin-top-10">
                                <span class="form-title">@lang('site.terms.title')</span> <br />
                                <a href="#" data-toggle="modal" data-target="#termsModal">@lang('site.terms.accept_info')</a>
                                <div class="form-group{{$errors->has('terms') ? ' has-error' : '' }}">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="terms" value="1"> @lang('site.terms.accept')
                                        </label>
                                    </div>
                                    @if ($errors->has('terms'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('terms') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-md-8 col-md-offset-2 margin-top-10">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="glyphicon glyphicon-ok-sign"></i> @lang('site.buttons.send')
                                    </button>
                                </div>
                            </div>
                        </div>

                    </form>
                @endif

                </div>
            </div>
        </div>
    </div>
</div>


<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="termsModalLabel">@lang('site.terms.title')</h4>
      </div>
      <div class="modal-body">
            @lang('site.terms.text')
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>
@endsection
