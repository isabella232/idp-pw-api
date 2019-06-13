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
    'Auth.MissingClientID' => 'Client ID est manquant',
    'Method.AlreadyExists' => 'La méthode de récupération existe déjà',
    'Method.CodeExpired' => 'Code de vérification invalide',
    'Method.CodeMissing' => 'Code est requis',
    'Method.EmailReuseError' => 'Adresse électronique principale fournie comme autre méthode de récupération.',
    'Method.InvalidCode' => 'Code de vérification invalide',
    'Method.InvalidEmail' => 'Adresse email invalide fournie',
    'Method.MissingValue' => 'Valeur est requise',
    'Method.NotFound' => 'Méthode de récupération non trouvée',
    'Method.PersonnelError' => 'Erreur de localisation du dossier personnel',
    'Method.TooManyFailures' => 'Trop d\'échecs pour cette méthode de récupération',
    'Mfa.AlreadyExists' => 'Cette 2SV existe déjà',
    'Mfa.InvalidCode' => 'Code invalide fourni',
    'Mfa.MissingValue' => 'Valeur est requise',
    'Mfa.RateLimitFailure' => 'Échec de limite de taux MFA',
    'Mfa.RecordNotFound' => 'Enregistrement MFA non trouvé',
    'Mfa.TypeMissing' => '\'Type\' est requis',
    'Mfa.UpdateFailure' => 'Echec de la mise à jour MFA',
    'Mfa.VerifyFailure' => 'MFA vérifier l\'échec',
    'Multiple.SetPartialSuccess {successes} {errors}' => 'Successfully set the password in {successes}, but failed to set the password in {errors}',
    'Multiple.SetFailed {errors}' => 'Failed to set the password in {errors}',
    'Password.Breached' => 'Le mot de passe que vous avez entré a déjà été découvert dans une violation de données d\'un site Web différent. Votre compte a peut-être été compromis ou non. Veuillez utiliser un mot de passe différent ici, puis visitez <a href="https://idphelp.sil.org/logging-in/password/password-recommendations#h.p_LOkQcA18P0qs" target="_blank">cette page d’aide</a> pour en savoir plus.',
    'Password.DisallowedContent' => 'Votre mot de passe ne peut contenir aucun de ceux-ci: {labelList} (code 180)',
    'Password.MissingPassword' => 'Mot de passe requis',
    'Password.PasswordReuse' => 'Impossible de mettre à jour le mot de passe. Si ce mot de passe a déjà été utilisé, veuillez utiliser quelque chose de différent.',
    'Password.PublicPasswordUsed' => 'Ha ha, bon. Non, vous ne devriez pas utiliser un mot de passe que vous avez vu dans une vidéo pour créer de bons mots de passe.',
    'Password.TooLong' => 'Votre mot de passe dépasse la longueur maximale de {maxLength} (code 110)',
    'Password.TooShort' => 'Votre mot de passe ne respecte pas la longueur minimale de {minLength} (code 100)',
    'Password.TooWeak' => 'Votre mot de passe ne répond pas à la force minimale de {minScore} (code 150)',
    'Password.UnknownProblem' => 'Il y a eu une erreur dans la recherche de sous-titres, veuillez réessayer. Si le problème persiste, veuillez contacter le support technique.',
    'Password.UpdateError' => 'Impossible de mettre à jour le mot de passe, attendez une minute, puis réessayez. Si ce problème persiste, veuillez contacter le support.',
    'Password.UpdateFailure' => 'Impossible de mettre à jour le mot de passe. S\'il vous plaît contacter le support.',
    'Reset.CannotEnable' => 'Impossible d\'activer la réinitialisation.',
    'Reset.CreateFailure' => 'Impossible de créer une nouvelle réinitialisation',
    'Reset.IncrementAttemptsError' => 'Impossible d\'incrémenter les tentatives comptent.',
    'Reset.InvalidCode' => 'Code de vérification invalide',
    'Reset.MissingClientID' => 'ID client manquant',
    'Reset.MissingCode' => 'Code est requis',
    'Reset.MissingMethodUID' => 'Méthode UID requise pour la méthode de type de réinitialisation',
    'Reset.MissingRecaptchaCode' => 'Le code de vérification reCAPTCHA est requis',
    'Reset.MissingResetType' => 'Type de réinitialisation invalide',
    'Reset.MissingUsername' => 'Valeur est requise',
    'Reset.NotFound' => 'Réinitialiser introuvable',
    'Reset.PasswordResetForSubject' => '{idpDisplayName} demande de réinitialisation du mot de passe pour {name}',
    'Reset.PasswordResetSubject' => '{idpDisplayName} demande de réinitialisation du mot de passe',
    'Reset.RecaptchaFailedVerification' => 'reCAPTCHA a échoué la vérification',
    'Reset.SetDisableTimeError' => 'Impossible d\'enregistrer la réinitialisation avec disable_until.',
    'Reset.UnknownType' => 'Type de réinitialisation inconnu demandé',
    'Reset.UpdateFailure' => 'Impossible de mettre à jour la réinitialisation dans la base de données, e-mail non envoyé.',
    'Reset.UpdateTypeError' => 'Impossible de mettre à jour le type de réinitialisation.',
    'Reset.UserNotFound' => 'Utilisateur introuvable',
    'Utils.InvalidEmail' => 'Adresse email invalide fournie',
    'Utils.RecaptchaVerifyFailure' => 'Impossible de vérifier reCAPTCHA',
    'Zxcvbn.LowScore' => 'Le mot de passe n\'a pas atteint la valeur minimale de {minScore}Essayez d\'ajouter des mots, mais évitez les expressions courantes.',
];
