<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Partner;
use App\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManageDonorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_donor_list_in_donor_index_page()
    {
        $creator = $this->loginAsUser();
        $partner = factory(Partner::class)->create(['type_code' => ['donatur'], 'creator_id' => $creator->id]);
        $this->visitRoute('donors.search');

        $this->seeText($partner->name);
    }

    /** @test */
    public function user_can_create_a_donor()
    {
        $this->loginAsUser();
        config(['partners.partner_levels' => 'donatur:silver|Silver|gold|Gold']);
        $this->visitRoute('donors.search');

        $this->click(__('donor.create'));
        $this->seeRouteIs('donors.create');

        $this->submitForm(__('donor.create'), [
            'name' => 'Donor 1 name',
            'phone' => '081234567890',
            'gender_code' => 'f',
            'work' => 'Dokter',
            'description' => 'Donor 1 description',
            'level_code' => 'silver',
            'address' => 'Donor 1 address',
        ]);

        $this->seeRouteIs('donors.search');

        $this->seeInDatabase('partners', [
            'name' => 'Donor 1 name',
            'phone' => '081234567890',
            'work' => 'Dokter',
            'gender_code' => 'f',
            'description' => 'Donor 1 description',
            'address' => 'Donor 1 address',
            'type_code' => json_encode(['donatur']),
            'level_code' => json_encode(['donatur' => 'silver']),
        ]);
    }

    /** @test */
    public function user_can_see_donor_detail()
    {
        $creator = $this->loginAsUser();
        $partner = factory(Partner::class)->create(['type_code' => ['donatur'], 'creator_id' => $creator->id]);
        $this->visitRoute('donors.search');
        $this->seeElement('a', ['id' => 'show-partner-'.$partner->id]);

        $this->click('show-partner-'.$partner->id);

        $this->seeRouteIs('donors.show', $partner);
        $this->seeText($partner->name);
    }

    /** @test */
    public function user_can_edit_a_donor()
    {
        $creator = $this->loginAsUser();
        config(['partners.partner_levels' => 'donatur:donatur_tetap|Donatur Tetap|terdaftar|Terdaftar']);
        $partner = factory(Partner::class)->create(['type_code' => ['donatur']]);

        $this->visitRoute('donors.show', $partner);
        $this->click('edit-partner-'.$partner->id);

        $this->seeRouteIs('donors.edit', $partner);

        $this->submitForm(__('donor.update'), [
            'name' => 'Donor 2 name',
            'phone' => '081234567890',
            'work' => 'Dokter',
            'gender_code' => 'm',
            'description' => 'Donor 2 description',
            'address' => 'Donor 2 address',
            'level_code' => 'donatur_tetap',
            'is_active' => 0,
        ]);

        $this->seeRouteIs('donors.show', $partner);

        $this->seeInDatabase('partners', [
            'name' => 'Donor 2 name',
            'phone' => '081234567890',
            'work' => 'Dokter',
            'description' => 'Donor 2 description',
            'address' => 'Donor 2 address',
            'type_code' => json_encode(['donatur']),
            'level_code' => json_encode(['donatur' => 'donatur_tetap']),
            'is_active' => 0,
        ]);
    }

    /** @test */
    public function user_can_delete_a_donor()
    {
        $creator = $this->loginAsUser();

        $partner = factory(Partner::class)->create(['type_code' => ['donatur'], 'creator_id' => $creator->id]);

        $this->visitRoute('donors.show', $partner);
        $this->click('edit-partner-'.$partner->id);
        $this->click('del-partner-'.$partner->id);

        $this->seeRouteIs('donors.edit', [$partner->id, 'action' => 'delete']);

        $this->press(__('app.delete_confirm_button'));
        $this->seeText(__('donor.deleted'));

        $this->dontSeeInDatabase('partners', [
            'id' => $partner->id,
        ]);
    }

    /** @test */
    public function user_cannot_delete_a_donor_that_has_transactions()
    {
        $creator = $this->loginAsUser();
        $partner = factory(Partner::class)->create(['type_code' => ['donatur'], 'creator_id' => $creator->id]);
        $book = factory(Book::class)->create();
        $transaction = factory(Transaction::class)->create(['partner_id' => $partner->id, 'book_id' => $book->id]);

        $this->visitRoute('donors.show', $partner);
        $this->click('edit-partner-'.$partner->id);
        $this->click('del-partner-'.$partner->id);

        $this->seeRouteIs('donors.edit', [$partner->id, 'action' => 'delete']);

        $this->dontSeeText(__('app.delete_confirm_button'));
        $this->seeText(__('donor.undeleteable'));
    }

    /** @test */
    public function validate_donor_phone_number_entries()
    {
        $this->loginAsUser();

        // Valid numbers
        $validCases = [
            [
                'phone' => '+6281234567890',
                'desc' => 'Indonesian number with country code',
            ],
            [
                'phone' => '081234567890',
                'desc' => 'Indonesian number without country code',
            ],
            [
                'phone' => '+12125551234',
                'desc' => 'US number',
            ],
        ];

        foreach ($validCases as $case) {
            $this->post(route('donors.store'), [
                'phone' => $case['phone'],
            ])->assertSessionMissingErrors('phone');
        }

        // Invalid numbers
        $invalidCases = [
            [
                'phone' => '123 456 789',
                'desc' => 'contains spaces',
            ],
            [
                'phone' => '123-456-789',
                'desc' => 'contains hyphens',
            ],
            [
                'phone' => '+abc12345678',
                'desc' => 'contains letters',
            ],
            [
                'phone' => '123456789',
                'desc' => 'too short (9 digits)',
            ],
            [
                'phone' => '1234567890123456',
                'desc' => 'too long (16 digits)',
            ],
            [
                'phone' => '++6281234567890',
                'desc' => 'multiple plus signs',
            ],
        ];

        foreach ($invalidCases as $case) {
            $this->post(route('donors.store'), [
                'phone' => $case['phone'],
            ])->assertSessionHasErrors('phone');
        }
    }
}
