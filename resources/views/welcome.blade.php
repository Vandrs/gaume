@extends('layouts.site')
@section('content')
    <div class="container" id="home">
        <div class='row'>
            <div class="col-xs-12 col-md-10">
                <div class="title-area left">
                    <h1>@lang('site.pages.home.body.title')</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="container section-content" id="about">
        <div class="row">
            <div class="col-xs-12 col-md-10 col-md-offset-1 text-center">
                <span class="what-is">@lang('site.pages.home.body.what_is')</span>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-10 col-md-offset-1 text-center">
                <h2>@lang('site.pages.home.body.subtitle')</h2>
            </div>
            <div class="col-xs-12 col-md-10 col-md-offset-1 text-center text-about">
                @lang('site.pages.home.body.area_text_1')
            </div>
            <div class="margin-top-10 col-xs-12 col-md-10 col-md-offset-1 hidden-xs hidden-sm">
                <div class="game-cover">
                </div>
            </div>
        </div>
    </div>

    <div class="we-bg">
    <div class="container section-content" id="we">
        <div class="row">
            <div class="col-xs-12 col-md-10 col-md-offset-1">
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <span class="who-we-are">@lang('site.pages.home.body.who_we_are')</span>
                    </div>
                </div>
                <div class="row margin-top-20">
                    <div class="col-xs-12 col-md-6 margin-top-10">
                        <div class="student-info">
                            <h2>@lang('site.students')</h2>
                            <p>@lang('site.pages.home.body.student_info')</p>
                            <h3><a href="{{url('/register')}}">@lang('site.be_student')</a></h3>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 margin-top-10">
                        <div class="teacher-info">
                            <h2>@lang('site.teachers')</h2>
                            <p>@lang('site.pages.home.body.teacher_info')</p>
                            <h3><a href="#">@lang('site.be_teacher')</a></h3>
                        </div>
                    </div>
                </div>
                <div class="row margin-top-20 margin-bottom-20">
                    <div class="col-xs-12">
                        <div class='monzy-team'>
                            <h3>@lang('site.monzy_team')</h3>
                            <p>@lang('site.pages.home.body.monzy_team_info')</p>
                            <a href="#contact"><strong>@lang('site.talk_to_us')</strong></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="bg-games-content">
        <div class="container section-games text-center">
            <div class="row margin-bottom-20">
                <div class="col-xs-12 col-md-10 col-md-offset-1">
                    <div class="row">
                        <div class='col-xs-12'>
                            <span class="what-is">@lang('site.pages.home.body.what_games')</span>
                        </div>
                    </div>
                    <div class="row margin-top-20">
                        <div class='col-xs-12'>
                            <span>@lang('site.pages.home.body.games-text')</span>
                        </div>
                    </div>
                    <div class="row margin-top-10">
                        <div class="col-xs-12 col-md-4">
                            <div class="game-bg dota">
                                <div class="layer">
                                    <div class="game-content">
                                        <span class="fa-stack fa-lg">
                                            <i class="fa fa-circle fa-stack-2x yellow-icon"></i>
                                            <i class="fa fa-laptop fa-stack-1x fa-inverse"></i>
                                        </span>
                                        <h2>Dota 2</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="game-bg lol">
                                <div class="layer">
                                    <div class="game-content">
                                        <span class="fa-stack fa-lg">
                                            <i class="fa fa-circle fa-stack-2x yellow-icon"></i>
                                            <i class="fa fa-laptop fa-stack-1x fa-inverse"></i>
                                        </span>
                                        <h2>League Of Legends</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="game-bg overwatch">
                                <div class="layer">
                                    <div class="game-content">
                                        <span class="fa-stack fa-lg">
                                            <i class="fa fa-circle fa-stack-2x yellow-icon"></i>
                                            <i class="fa fa-gamepad fa-stack-1x fa-inverse"></i>
                                        </span>
                                        <span class="fa-stack fa-lg">
                                            <i class="fa fa-circle fa-stack-2x yellow-icon"></i>
                                            <i class="fa fa-laptop fa-stack-1x fa-inverse"></i>
                                        </span>
                                        <h2>Overwatch</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
            <div class="col-xs-12 col-md-6 col-md-offset-3 text-center">
                <h3>@lang('site.pages.home.body.contact_title')</h3>
            </div>
        </div>
        <div class='row margin-top-10'>
            <div class="col-xs-12 col-md-4 col-md-offset-4">
                <div class="leadFormArea">
                    <form id="leadForm" action="#" method="POST">
                        <input type="hidden" name="type" id="type" value="{{EnumContactType::SITE}}">
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
                    <li><a href="{{url('/documentos/TERMOS E CONDIÇÕES DA MONZY OFICIAL.pdf')}}" target="_blank">@lang('site.menu.terms')</a></li>
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