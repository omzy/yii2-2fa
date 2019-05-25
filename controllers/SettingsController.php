<?php

namespace app\controllers;

use Yii;
use app\services\TwoFactorQrCodeUriGeneratorService;
use app\validators\TwoFactorCodeValidator;

class SettingsController extends Controller
{
    /** 2FA cycles */
    const $cycles = 50;
    
    /**
     * Display page with QR code
     *
     * @param int $id
     * @return string
     */
    public function actionTwoFactor($id)
    {
        // @var User
        $user = $this->user;
        
        // Create the QR code
        $uri = Yii::createObject(TwoFactorQrCodeUriGeneratorService::class, [$user])->run();

        return $this->render('two-factor', [
            'id' => $id,
            'uri' => $uri,
            'code' => $user->auth_tf_key,
        ]);
    }
    
    /**
     * Enable 2FA
     *
     * @param int $id
     * @param string $code
     * @return string
     */
    public function actionTwoFactorEnable($id, $code)
    {
        // @var User
        $user = $this->user;

        $success = Yii::createObject(TwoFactorCodeValidator::class, [$user, $code, $this->cycles])->validate();
        $success = $success && $user->updateAttributes(['auth_tf_enabled' => 1]);
        $message = $success ? 'Two factor authentication successfully enabled.' : 'Verification failed. Please, enter new code.';

        return $this->asJson([
            'success' => $success,
            'message' => $message,
        ]);
    }
    
    /**
     * Disable 2FA
     *
     * @param int $id
     * @return string
     */
    public function actionTwoFactorDisable($id)
    {
        // @var User
        $user = $this->user;

        if ($user->updateAttributes(['auth_tf_enabled' => 0])) {
            Yii::$app->session->setFlash('success', 'Two-Factor authentication has been disabled.');
        }

        $this->redirect(['index']);
    }
}