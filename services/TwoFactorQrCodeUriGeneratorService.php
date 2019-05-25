<?php

namespace app\services;

use Yii;
use Da\TwoFA\Manager;
use Da\TwoFA\Service\QrCodeDataUriGeneratorService;
use Da\TwoFA\Service\TOTPSecretKeyUriGeneratorService;
use app\models\User;

class TwoFactorQrCodeUriGeneratorService
{
    /**
     * @var User
     */
    protected $user;

    /**
     * TwoFactorQrCodeUriGeneratorService constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $user = $this->user;
        
        if (empty($user->auth_tf_key)) {
            $user->auth_tf_key = (new Manager())->generateSecretKey();
            $user->updateAttributes(['auth_tf_key']);
        }

        $totpUri = (new TOTPSecretKeyUriGeneratorService(Yii::$app->name, $user->email, $user->auth_tf_key))->run();

        return (new QrCodeDataUriGeneratorService($totpUri))->run();
    }
}