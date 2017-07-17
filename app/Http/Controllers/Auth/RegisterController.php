<?php

namespace App\Http\Controllers\Auth;

use Config;
use Lang;
use Log;
use DB;
use App\Utils\Util;
use App\Http\Controllers\Controller;
use App\AssetLoader\AssetLoader;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Registration\StudentRegistrationService;
use App\Services\Registration\TeacherRegistrationService;
use App\Services\Registration\GetPreRegistrationService;
use App\Services\Registration\ValidadeRegistrationPeriodService;
use App\Exceptions\ValidationException;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/app/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        AssetLoader::registerSiteScript('registration.js');
        $pageTitle = Lang::get('site.registration.register');
        return view('auth.register',[
            'pageTitle' => $pageTitle
        ]);
    }

    public function register(Request $request)
    {
        try {
            DB::beginTransaction();
            $userRegistration = new StudentRegistrationService();
            $user = $userRegistration->registerUser($request->all(), $request->file('photo_profile'));
            $this->guard()->login($user);
            DB::commit();
            return $this->registered($request, $user) ? : redirect($this->redirectPath());
        } catch (ValidationException $e) {
            DB::rollback();
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            return back()->withInput()->withErrors($userRegistration->getValidator());
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            session()->flash('flash_error', Lang::get('validation.custom.unexpected'));
            return back()->withInput();
        }
    }

    public function showTeacherRegistrationForm(Request $request)
    {
        AssetLoader::registerSiteScript('registration.js');
        $session = session();
        $pageTitle = Lang::get('site.registration.register_teacher');
        try{
            $code = Util::coalesce(old('code'), $request->code);
            $preRegistration = GetPreRegistrationService::getByCode($code);
            $games = [];
            $preRegistration->preRegistrationPlatforms->each(function($registrationPlatform) use (&$games) {
                $gamePlatform = $registrationPlatform->gamePlatform;
                if (!isset($games[$gamePlatform->game_id])) {
                    $games[$gamePlatform->game_id] = [
                        'game' => $gamePlatform->game,
                        'platforms' => []
                    ];
                }
                $games[$gamePlatform->game_id]['platforms'][] = $gamePlatform->platform;
            });
        } catch (ModelNotFoundException $e) {
            $session->flash('flash_error', Lang::get('pre_registration.code_not_found'));
        } catch (ValidationException $e) {
            $session->flash('flash_error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            $session->flash('flash_error', Lang::get('validation.custom.unexpected'));
        }
        if ($session->has('flash_error')) {
            return view('auth.teacher-register',[
                'pageTitle' => $pageTitle
            ]);
        } else {
            return view('auth.teacher-register',[
                'pageTitle'       => $pageTitle,
                'preRegistration' => $preRegistration,
                'games'           => $games,
                'code'            => $code,
            ]);            
        }
    }

    public function teacherRegister(Request $request)
    {
        try {
            DB::beginTransaction();
            $userRegistration = new TeacherRegistrationService();
            $user = $userRegistration->registerUser($request->all(), $request->file('photo_profile'));
            $this->guard()->login($user);
            DB::commit();
            return $this->registered($request, $user) ? : redirect($this->redirectPath());
        } catch (ValidationException $e) {
            DB::rollback();
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            return back()->withInput()->withErrors($userRegistration->getValidator());
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            session()->flash('flash_error', Lang::get('validation.custom.unexpected'));
            return back()->withInput();
        }
    }
}
