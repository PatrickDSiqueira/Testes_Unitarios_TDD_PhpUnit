<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class USerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @test
     */
    public function check_if_user_columns_is_correct()
    {
        $user = new User;

        $expected = [
          'name',
          'email',
          'password'
        ];

//        UnitOfWork_StateUnderTest_ExpectedBehavior
//        Action_WhoOrWhatToDo_ExpectedBehavior
//        check_if _user_columns _is_correct

        $arrayCompared = array_diff($expected, $user->getFillable());

        $this->assertEquals(0, count($arrayCompared));
    }
}
