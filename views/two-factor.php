<?php
/** @var string $id */
/** @var string $uri */
/** @var string $code */

use yii\helpers\Url;
?>

<div class="content">
    <h1>Two-Factor Authentication</h1>

    <p>Two factor authentication provides you with an additional level of security during account login.</p>

    <div class="alert alert-info">
        <p>Scan the QR code below with your two-factor authenticator app, or manually enter this code: <code><?php echo $code; ?></code>. Then enter the 6 digit authentication code in the box below.</p>
    </div>

    <div class="row">
        <div class="col-md-offset-3 col-md-6 text-center">
            <img id="qr-code" src="<?php echo $uri; ?>" alt="QR Code">
        </div>
    </div>

    <div class="row">
        <form id="enable-tf-auth" action="<?php echo Url::to(['two-factor-enable', 'id' => $id]); ?>" autocomplete="off">
            <div class="col-md-offset-3 col-md-6 text-center"">
                <div class="input-group">
                    <input type="text" name="code" class="form-control" placeholder="Enter Authentication Code">

                    <div class="input-group-btn">
                        <button type="submit" class="btn btn-primary btn-submit-code">Enable</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>