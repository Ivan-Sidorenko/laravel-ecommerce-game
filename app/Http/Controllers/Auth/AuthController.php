<?php

namespace App\Http\Controllers\Auth;

use App\Repositories\UserRepository;
use App\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * @var Socialite
     */
    private $socialite;

    /**
     * @var Guard
     */
    private $auth;

    /**
     * @var UserRepository
     */
    private $users;

    /**
     * @var string
     */
    protected $redirectPath = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @param Socialite $socialite
     * @param Guard $auth
     * @param UserRepository $users
     */
    public function __construct(Socialite $socialite, Guard $auth, UserRepository $users)
    {
        $this->middleware('guest', ['except' => 'getLogout']);

        $this->socialite = $socialite;
        $this->users = $users;
        $this->auth = $auth;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            'terms_and_conditions' => 'required',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * @return string
     */
    public function loginPath()
    {
        return '/';
    }

    /**
     * Redirect the user to the Social authentication page.
     *
     * @param string $driver
     * @return Response
     */
    public function redirectToProvider($driver)
    {
        return $this->socialite->driver($driver)->redirect();
    }

    /**
     * Obtain the user information from Social.
     *
     * @param string $driver
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handleProviderCallback($driver)
    {
        $userData = $this->socialite->driver($driver)->user();

        $user = $this->users->findBySocialEmailOrCreate($userData, $driver);

        $this->auth->login($user, true);

        Session::flash('success', 'Welcome, ' . $user->name);

        return redirect('/');
    }
}
