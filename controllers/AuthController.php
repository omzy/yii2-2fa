<?php

namespace app\controllers;

use Yii;
use app\models\LoginForm;

class AuthController extends Controller
{
    /**
     * Two factor authentication
     *
     * @return string|Response
     */
    public function actionAuthenticate()
    {
        // ensure login credentials session key is present
        if (!Yii::$app->session->has('credentials')) {
            return $this->redirect(['login']);
        }

        // get the login credentials from the session
        $credentials = Yii::$app->session->get('credentials');

        /** @var LoginForm $model */
        $model = new LoginForm();
        $model->email = $credentials['email'];
        $model->password = $credentials['password'];
        $model->setScenario('2fa');

        // get form post
        if ($model->load(Yii::$app->request->post())) {
            // validate and log in user
            if ($model->login()) {
                // clear out the login credentials from session
                Yii::$app->session->set('credentials', null);

                return $this->goBack();
            }
        }

        return $this->render('authenticate', [
            'model' => $model,
        ]);
    }
}
