# Yii 2 - Two Factor Authentication

This is an extract of code from an application I built in Yii 2. It demonstrates a Two Factor Authentication (2FA) feature.

The implementation is based on the [2amigos 2fa library](https://github.com/2amigos/2fa-library).

An overview of the files is as follows:

   - [`controllers/AuthController`](controllers/AuthController.php): This contains the action to receive the form post that includes the 2FA code
   - [`controllers/SettingsController`](controllers/SettingsController.php): This contains the actions that allow the user to view, enable and disable 2FA
   - [`models/LoginForm`](models/LoginForm.php): This contains the logic to process and validate the 2FA login form
   - [`services/TwoFactorQrCodeUriGeneratorService`](services/TwoFactorQrCodeUriGeneratorService.php): This contains the logic to generate a 2FA QR code
   - [`validators/TwoFactorCodeValidator`](validators/TwoFactorCodeValidator.php): This contains a custom validator that performs the 2FA code validation
   - [`views/authenticate.php`](views/authenticate.php): This contains the form where the user will enter the 2FA code (screenshot below)
   - [`views/two-factor.php`](views/two-factor.php): This contains the form that allows the user to view, enable and disable 2FA

![screenshot](2fa.png)
