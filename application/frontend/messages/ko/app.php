<?php
/**
 * Message translations.
 *
 * This file is automatically generated by 'yii message/extract' command.
 * It contains the localizable messages extracted from source code.
 * You may modify this file by translating the extracted messages.
 *
 * Each array element represents the translation (value) of a message (key).
 * If the value is empty, the message is considered as not translated.
 * Messages that no longer need translation will have their translations
 * enclosed between a pair of '@@' marks.
 *
 * Message string can be used with plural forms format. Check i18n section
 * of the guide for details.
 *
 * NOTE: this file must be saved in UTF-8 encoding.
 */
return [
    'Auth.MissingClientID' => 'Client ID is missing',
    'Method.AlreadyExists' => 'Recovery method already exists',
    'Method.CodeExpired' => 'Expired verification code',
    'Method.CodeMissing' => 'Code is required',
    'Method.EmailReuseError' => 'Primary email address supplied as alternate recovery method.',
    'Method.InvalidCode' => 'Invalid verification code',
    'Method.InvalidEmail' => 'Invalid email address provided',
    'Method.MissingValue' => 'Value is required',
    'Method.NotFound' => 'Recovery method not found',
    'Method.PersonnelError' => 'Error locating personnel record',
    'Method.TooManyFailures' => 'Too many failures for this recovery method',
    'Mfa.AlreadyExists' => 'This 2SV already exists',
    'Mfa.InvalidCode' => 'Invalid code provided',
    'Mfa.MissingValue' => 'Value is required',
    'Mfa.RateLimitFailure' => 'MFA rate limit failure',
    'Mfa.RecordNotFound' => 'MFA record not found',
    'Mfa.TypeMissing' => 'Type is required',
    'Mfa.UpdateFailure' => 'MFA update failure',
    'Mfa.VerifyFailure' => 'MFA verify failure',
    'Password.Breached' => 'The password you entered was previously discovered in a data breach of a different website. It may or may not have been your own account that was compromised. Please use a different password here and then visit <a href="https://idphelp.sil.org/logging-in/password/password-recommendations#h.p_LOkQcA18P0qs" target="_blank">this help page</a> to learn more.',
    'Password.DisallowedContent' => 'Your password may not contain any of these: {labelList} (code 180)',
    'Password.MissingPassword' => 'Password is required',
    'Password.PasswordReuse' => 'Unable to update password. If this password has been used before please use something different.',
    'Password.PublicPasswordUsed' => 'Ha ha, good one. No, you shouldn\'t use a password you saw in a video about creating good passwords.',
    'Password.TooLong' => 'Your password exceeds the maximum length of {maxLength} (code 110)',
    'Password.TooShort' => 'Your password does not meet the minimum length of {minLength} (code 100)',
    'Password.TooWeak' => 'Your password does not meet the minimum strength of {minScore} (code 150)',
    'Password.UnknownProblem' => 'There was a problem with your password. Please try again. If this problem persists, please contact support.',
    'Password.UpdateError' => 'Unable to update password, please wait a minute and try again. If this problem persists, please contact support.',
    'Password.UpdateFailure' => 'Unable to update password. Please contact support.',
    'Reset.CannotEnable' => 'Unable to enable reset.',
    'Reset.CreateFailure' => 'Unable to create new reset',
    'Reset.IncrementAttemptsError' => 'Unable to increment attempts count.',
    'Reset.InvalidCode' => 'Invalid verification code',
    'Reset.MissingClientID' => 'Missing Client ID',
    'Reset.MissingCode' => 'Code is required',
    'Reset.MissingMethodUID' => 'Method UID required for reset type method',
    'Reset.MissingRecaptchaCode' => 'reCAPTCHA verification code is required',
    'Reset.MissingResetType' => 'Invalid reset type',
    'Reset.MissingUsername' => 'Value is required',
    'Reset.NotFound' => 'Reset not found',
    'Reset.PasswordResetForSubject' => '{idpDisplayName} password reset request for {name}',
    'Reset.PasswordResetSubject' => '{idpDisplayName} password reset request',
    'Reset.RecaptchaFailedVerification' => 'reCAPTCHA failed verification',
    'Reset.SetDisableTimeError' => 'Unable to save reset with disable_until.',
    'Reset.UnknownType' => 'Unknown reset type requested',
    'Reset.UpdateFailure' => 'Unable to update reset in database, email not sent.',
    'Reset.UpdateTypeError' => 'Unable to update reset type.',
    'Reset.UserNotFound' => 'User not found',
    'Utils.InvalidEmail' => 'Invalid email address provided',
    'Utils.RecaptchaVerifyFailure' => 'Unable to verify reCAPTCHA',
    'Zxcvbn.LowScore' => 'Password did not meet minimum strength of {minScore}.Try adding some words but avoid common phrases.',
];
