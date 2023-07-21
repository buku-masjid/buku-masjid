<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LangSwitcherTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_switch_en_lang()
    {
        $user = $this->loginAsUser();

        $this->visitRoute('profile.show');
        $this->seeElement('button', ['id' => 'lang_en']);

        $this->press('lang_en');

        $this->seeInSession('lang', 'en');
    }

    /** @test */
    public function user_can_switch_id_lang()
    {
        $user = $this->loginAsUser();

        $this->visitRoute('profile.show');
        $this->seeElement('button', ['id' => 'lang_id']);

        $this->press('lang_id');

        $this->seeInSession('lang', 'id');
    }
}
