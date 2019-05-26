<?php

namespace app\controllers;

use Yii;
use app\models\LoginForm;

class AuthController extends Controller
{
    /**
     * Displays the login page.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        // if user is already logged in, redirect to account
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['account/index']);
        }

        /** @var LoginForm $loginForm */
        $model = new LoginForm();
        $model->setScenario('login');

        // get form post
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                // check if the user has 2FA enabled
                if ($model->getUser()->auth_tf_enabled) {
                    // set login credentials in session
                    Yii::$app->session->set('credentials', ['username' => $model->username, 'password' => $model->password]);

                    // redirect to 2FA login page
                    return $this->redirect(['authenticate']);
                }
                // else login the user
                elseif ($model->login()) {
                    return $this->goBack();
                }
            }
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

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
        $model->username = $credentials['username'];
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
