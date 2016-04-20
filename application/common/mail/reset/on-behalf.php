Hi there,

<?php echo \yii\helpers\Html::encode($name); ?> recently requested a password change for their
<?php echo \Yii::$app->params['appName']; ?> account and they have requested your assistance.

Please contact them directly to ensure that you are only providing the following reset code to them
and not to someone else.

Reset Code: <?php echo \yii\helpers\Html::encode($resetCode); ?>

To keep their account secure, please don't forward this email to anyone.
See our Help Center for <a href="<?php echo \Yii::$app->params['helpCenterUrl']; ?>">more security tips</a>.

Thanks!
- <?php echo \yii\helpers\Html::encode(\Yii::$app->params['fromName']); ?>