@extends('layouts.site')
@section('content')
    <div class="bg-form-content">  
    <div class="container section-form" id="contact">
        <div class="row">
            <div class="col-xs-12 col-md-4 col-md-offset-4 text-center icon-area">
                <span class="fa-stack fa-lg">
                    <i class="fa fa-circle fa-stack-2x yellow-icon"></i>
                    <i class="fa fa-gamepad fa-stack-1x fa-inverse"></i>
                </span>
            </div>
        </div>
        <div class='row margin-top-10 margin-bottom-10'>
            <div class="col-xs-12 col-md-10 col-md-offset-1 text-center">
                <h1>@lang('site.contact.title')</h1>
            </div>
        </div>
        <div class='row margin-top-10'>
            <div class="col-xs-12 col-md-4 col-md-offset-4">
                <div class="leadFormArea">
                    <form id="leadForm" action="#" method="POST">
                        <input type="hidden" name="type" id="type" value="{{EnumContactType::TEACHER_INTEREST}}">
                        <div class="row">
                            <div class="col-xs-12 alert-container">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="name" class="control-label">Nome</label>
                                    <input type="text" name="name" id="name" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="email" class="control-label">E-mail</label>
                                    <input type="text" name="email" id="email" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="comment" class="control-label">Comentário</label>
                                    <textarea name="comment" id="comment" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <button type="submit" id="submitForm" class="btn btn-default">Play</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="section-footer ">
    <div class="container text-center">
        <div class="row">
            <div class="col-xs-12 footer">
                <ul class="footer-menu">
                    <li><a href="#home">@lang('site.menu.home')</a></li>
                    <li><a href="#about">@lang('site.menu.about')</a></li>
                    <li><a href="#we">@lang('site.menu.we')</a></li>
                    <li><a href="#contact">@lang('site.menu.contact')</a></li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 copyright">
                @lang('site.copyright')
            </div>
        </div>
    </div>
    </div>


@endsection