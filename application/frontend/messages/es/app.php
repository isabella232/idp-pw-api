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
    'Auth.MissingClientID' => 'Falta el ID del cliente',
    'Method.AlreadyExists' => 'El método de pago ya existe',
    'Method.CodeExpired' => 'Código de verificación vencido',
    'Method.CodeMissing' => 'Es necesario un código',
    'Method.EmailReuseError' => 'Dirección de correo electrónico principal suministrada como método de recuperación alternativo.',
    'Method.InvalidCode' => 'Código de verificación no válido',
    'Method.InvalidEmail' => 'Dirección de correo electrónico no válida "%s"',
    'Method.MissingValue' => 'El valor es requerido',
    'Method.NotFound' => 'El Hash de recuperación no ha sido encontrado',
    'Method.PersonnelError' => 'Error al localizar el registro de personal',
    'Method.TooManyFailures' => 'Demasiados fallos para este método de recuperación',
    'Mfa.AlreadyExists' => 'La 2SV ya existe',
    'Mfa.InvalidCode' => 'Pagina proveída no válida',
    'Mfa.MissingValue' => 'El valor es requerido',
    'Mfa.RateLimitFailure' => 'Falla de límite de tasa de AMF',
    'Mfa.RecordNotFound' => 'Registro no encontrado',
    'Mfa.TypeMissing' => 'Se requiere el tipo',
    'Mfa.UpdateFailure' => 'Fallo al actualizar',
    'Mfa.VerifyFailure' => 'MFA verificar fallo',
    'Password.Breached' => 'La contraseña que ingresó fue descubierta previamente en una violación de datos de un sitio web diferente. Puede o no haber sido su propia cuenta la que se vio comprometida. Utilice una contraseña diferente aquí y luego visite <a href="https://idphelp.sil.org/logging-in/password/password-recommendations#h.p_LOkQcA18P0qs" target="_blank">esta página de ayuda</a> para obtener más información.',
    'Password.DisallowedContent' => 'Su contraseña no puede contener ninguno de estos: {labelList} (código 180)',
    'Password.MissingPassword' => 'Se requiere contraseña',
    'Password.PasswordReuse' => 'No se puede actualizar la contraseña. Si esta contraseña ha sido usada antes, use algo diferente.',
    'Password.PublicPasswordUsed' => 'Ja, ja, buena. No, no debe usar una contraseña que vio en un video sobre cómo crear buenas contraseñas.',
    'Password.TooLong' => 'Su contraseña excede la longitud máxima de {maxLength} (código 110)',
    'Password.TooShort' => 'Su contraseña excede la longitud máxima de {minLength} (código 100)',
    'Password.TooWeak' => 'Su contraseña excede la longitud máxima de {minScore} (código 150)',
    'Password.UnknownProblem' => 'Se ha producido un error al buscar subtítulos. Inténtalo de nuevo. Si el problema persiste, ponte en contacto con el servicio de asistencia.',
    'Password.UpdateError' => 'No se puede actualizar la contraseña, espere un minuto y vuelva a intentarlo. Si este problema persiste, póngase en contacto con el servicio de asistencia.',
    'Password.UpdateFailure' => 'No se puede actualizar la contraseña. Por favor, póngase en contacto con el soporte.',
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
