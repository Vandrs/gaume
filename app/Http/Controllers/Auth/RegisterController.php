<?php

namespace App\Http\Controllers\Auth;

use Config;
use Lang;
use Log;
use App\User;
use App\Http\Controllers\Controller;
use App\AssetLoader\AssetLoader;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Services\Location\GetStateService;
use App\Services\Registration\StudentRegistrationService;
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
        $states = GetStateService::getAll();
        $pageTitle = Lang::get('site.registration.register');
        return view('auth.register',[
            'pageTitle' => $pageTitle,
            'states' => $states
        ]);
    }

    public function showTeacherRegistrationForm(Request $request)
    {
        
    }

    public function register(Request $request)
    {
        try {
            $userRegistration = new StudentRegistrationService();
            $user = $userRegistration->registerUser($request->all(), $request->file('photo_profile'));
            $this->guard()->login($user);
            return $this->registered($request, $user) ? : redirect($this->redirectPath());
        } catch (ValidationException $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            return back()->withInput()->withErrors($userRegistration->getValidator());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            session()->flash('flash_error', Lang::get('validation.custom.unexpected'));
            return back()->withInput();
        }
    }
}
