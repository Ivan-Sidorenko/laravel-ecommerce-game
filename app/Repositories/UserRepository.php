<?php

namespace App\Repositories;
use App\User;
use Laravel\Socialite\Two\User as SocialUserOne;
use Laravel\Socialite\One\User as SocialUserTwo;

/**
 * UserRepository
 *
 * @author Alexander Begoon <alexander.begoon@gmail.com>
 */
class UserRepository
{
    /**
     * @param SocialUserOne|SocialUserTwo $userData
     * @param string $driver
     * @return static
     */
    public function findBySocialEmailOrCreate($userData, $driver) {

        $user = User::where('email', '=', $userData->email)->first();

        if(!$user) {
            $user = User::create([
                'social_provider_id' => $userData->id,
                'social_provider' => $driver,
                'social_name' => $userData->name,
                'name' => $userData->name,
                'social_username' => $userData->nickname,
                'email' => $userData->email,
                'social_avatar' => $userData->avatar,
            ]);
        }

        $this->checkIfUserNeedsUpdating($userData, $user, $driver);

        return $user;
    }

    /**
     * @param SocialUserOne|SocialUserTwo $userData
     * @param User $user
     * @param string $driver
     */
    public function checkIfUserNeedsUpdating($userData, $user, $driver) {

        $socialData = [
            'social_avatar' => $userData->avatar,
            'email' => $userData->email,
            'social_name' => $userData->name,
            'social_username' => $userData->nickname,
            'social_provider' => $driver,
        ];
        $dbData = [
            'social_avatar' => $user->social_avatar,
            'email' => $user->email,
            'social_name' => $user->social_name,
            'social_username' => $user->social_username,
            'social_provider' => $user->social_provider,
        ];

        if (!empty(array_diff($socialData, $dbData))) {
            $user->social_avatar = $userData->avatar;
            $user->email = $userData->email;
            $user->social_name = $userData->name;
            $user->social_username = $userData->nickname;
            $user->social_provider = $driver;
            $user->save();
        }
    }
}