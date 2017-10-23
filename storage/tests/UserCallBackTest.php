<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserCallBackTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * 提交用户反馈
     */
    public function testUserCallback()
    {
        $text = factory(App\FaqUserQuestionInfo::class)->make()->toArray();

        $this->post($this->createUrl('user-callback/save'), $text)
            ->successRemarkJson();

    }
}
