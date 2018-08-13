<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-08-12 14:11
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Tasks;

use CrCms\Foundation\App\Tasks\AbstractTask;
use CrCms\Passport\Models\UserModel;
use Illuminate\Support\Carbon;
use Tymon\JWTAuth\JWTGuard;

/**
 * Class JwtTask
 * @package CrCms\Passport\Tasks
 */
class JwtTask extends AbstractTask
{
    const JWT_NEW = 'new';

    const JWT_REFRESH = 'refresh';

    const JWT_REMOVE = 'remove';

    /**
     * @param mixed ...$params
     * @return array
     */
    public function handle(...$params): array
    {
        /* @var UserModel $user */
        /* @var array $cookie */
        /* @var string $appKey */
        list($user, $tokens, $appKey) = $params;

        $jwtToken = $this->auth->guard($this->config->get('auth.defaults.guard'))
            ->setTTL(Carbon::createFromTimestamp($tokens['expired_at'])->diffInMinutes(now()))
            ->fromUser($user->setJWTCustomClaims(['token' => $tokens['token'], 'app_key' => $appKey]));

        return [
            'token' => $jwtToken,
            'token_type' => 'Bearer',
            'expired_at' => $tokens['expired_at']
        ];
    }

    protected function new(UserModel $user, array $tokens, string $appKey): string
    {
        return $this->guard()
            ->setTTL(Carbon::createFromTimestamp($tokens['expired_at'])->diffInMinutes(now()))
            ->fromUser($user->setJWTCustomClaims(['token' => $tokens['token'], 'app_key' => $appKey]));
    }

    protected function refresh(array $tokens): string
    {
        return $this->guard()->setTTL(Carbon::createFromTimestamp($tokens['expired_at'])->diffInMinutes(now()))->refresh();
    }

    protected function guard(): JWTGuard
    {
        return $this->auth->guard($this->config->get('auth.defaults.guard'));
    }
}