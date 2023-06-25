<?php

namespace Tests\Feature;

use App\Models\BankAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManageBankAccountsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_bank_account_list_in_bank_account_index_page()
    {
        $creator = $this->loginAsUser();
        $bankAccount = factory(BankAccount::class)->create(['creator_id' => $creator->id]);
        $this->visit(route('bank_accounts.index'));

        $this->seeText($bankAccount->name);
    }

    /** @test */
    public function user_can_create_a_bank_account()
    {
        $this->loginAsUser();
        $this->visit(route('bank_accounts.index'));

        $this->click(__('bank_account.create'));
        $this->seePageIs(route('bank_accounts.index', ['action' => 'create']));

        $this->submitForm(__('bank_account.create'), [
            'name' => 'BankAccount 1 name',
            'number' => '1234567890',
            'account_name' => 'John Doe',
            'description' => 'BankAccount 1 description',
        ]);

        $this->seePageIs(route('bank_accounts.index'));

        $this->seeInDatabase('bank_accounts', [
            'name' => 'BankAccount 1 name',
            'number' => '1234567890',
            'account_name' => 'John Doe',
            'description' => 'BankAccount 1 description',
        ]);
    }

    /** @test */
    public function user_can_see_bank_account_detail()
    {
        $creator = $this->loginAsUser();
        $bankAccount = factory(BankAccount::class)->create(['creator_id' => $creator->id]);

        $this->visit(route('bank_accounts.index'));
        $this->seeElement('a', ['id' => 'show-bank_account-'.$bankAccount->id]);

        $this->click('show-bank_account-'.$bankAccount->id);

        $this->seeRouteIs('bank_accounts.show', $bankAccount);
        $this->seeText($bankAccount->name);
    }

    /** @test */
    public function user_can_edit_a_bank_account()
    {
        $creator = $this->loginAsUser();

        $bankAccount = factory(BankAccount::class)->create(['creator_id' => $creator->id]);

        $this->visit(route('bank_accounts.index'));
        $this->click('edit-bank_account-1');

        $this->seePageIs(route('bank_accounts.index', [
            'action' => 'edit', 'id' => $bankAccount->id,
        ]));

        $this->submitForm(__('bank_account.update'), [
            'name' => 'BankAccount 2 name',
            'number' => '1234567890',
            'account_name' => 'John Doe',
            'description' => 'BankAccount 2 description',
            'is_active' => 0,
        ]);

        $this->seePageIs(route('bank_accounts.index'));

        $this->seeInDatabase('bank_accounts', [
            'name' => 'BankAccount 2 name',
            'number' => '1234567890',
            'account_name' => 'John Doe',
            'description' => 'BankAccount 2 description',
            'is_active' => 0,
        ]);
    }

    /** @test */
    public function user_can_delete_a_bank_account()
    {
        $creator = $this->loginAsUser();

        $bankAccount = factory(BankAccount::class)->create(['creator_id' => $creator->id]);

        $this->visit(route('bank_accounts.index'));
        $this->click('edit-bank_account-1');
        $this->click('del-bank_account-'.$bankAccount->id);

        $this->seePageIs(route('bank_accounts.index', [
            'action' => 'delete', 'id' => $bankAccount->id,
        ]));

        $this->press(__('app.delete_confirm_button'));

        $this->dontSeeInDatabase('bank_accounts', [
            'id' => $bankAccount->id,
        ]);
    }
}
