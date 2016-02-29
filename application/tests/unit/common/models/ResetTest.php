<?php
namespace tests\unit\common\models;

use common\models\Method;
use common\models\User;
use common\models\Reset;
use yii\codeception\DbTestCase;

use tests\unit\fixtures\common\models\UserFixture;
use tests\unit\fixtures\common\models\MethodFixture;
use tests\unit\fixtures\common\models\ResetFixture;

/**
 * Class ResetTest
 * @package tests\unit\common\models
 * @method User users($key)
 * @method Method methods($key)
 * @method Reset resets($key)
 */
class ResetTest extends DbTestCase
{
    public function fixtures()
    {
        return [
            'users' => UserFixture::className(),
            'methods' => MethodFixture::className(),
            'resets' => ResetFixture::className(),
        ];
    }

    public function testDefaultValues()
    {
        Reset::deleteAll();
        $user1 = $this->users('user1');
        $reset = new Reset();
        $reset->user_id = $user1->id;
        if (!$reset->save()) {
            $this->fail('Failed to create Reset: '.print_r($reset->getFirstErrors(),true));
        }

        $this->assertEquals(32,strlen($reset->uid));
        $this->assertEquals(Reset::TYPE_PRIMARY, $reset->type);
        $this->assertNull($reset->method_id);
        $this->assertNull($reset->code);
        $this->assertEquals(0, $reset->attempts);
        $this->assertNotNull($reset->expires);
        $this->assertNull($reset->disable_until);
        $this->assertNotNull($reset->created);
    }

    public function testGetExpireTimestamp()
    {
        // Set config to consistent value
        \Yii::$app->params['reset']['lifetimeSeconds'] = 100;
        $time = time();

        $reset = $this->resets('reset1');

        $expireTimestamp = $reset->getExpireTimestamp($time);

        $this->assertEquals($time + 100, $expireTimestamp);
    }

    public function testCannotCreateSecondResetForUser()
    {
        $existing = $this->resets('reset1');

        $second = new Reset();
        $second->user_id = $existing->user_id;
        $this->assertFalse($second->save());
    }

    public function testFindOrCreateNew()
    {
        Reset::deleteAll();
        $user = $this->users('user1');

        $reset = Reset::findOrCreate($user);

        $this->assertEquals(32,strlen($reset->uid));
        $this->assertEquals(Reset::TYPE_PRIMARY, $reset->type);
        $this->assertNull($reset->method_id);
        $this->assertNull($reset->code);
        $this->assertEquals(0, $reset->attempts);
        $this->assertNotNull($reset->expires);
        $this->assertNull($reset->disable_until);
        $this->assertNotNull($reset->created);
    }

    public function testFindOrCreateExisting()
    {
        $existing = $this->resets('reset1');
        $new = Reset::findOrCreate($existing->user);

        $this->assertEquals($existing->id,$new->id);
    }


}