<?php

namespace Tests\Feature\Lecturing;

use App\Models\Lecturing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LecturingBrowseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_lecturing_list_in_lecturing_index_page()
    {
        $lecturing = factory(Lecturing::class)->create();

        $this->loginAsUser();
        $this->visitRoute('lecturings.index');
        $this->see($lecturing->lecturer_name);
    }

    /** @test */
    public function user_can_see_lecturing_detail_in_lecturing_show_page()
    {
        $lecturing = factory(Lecturing::class)->create([
            'audience_code' => Lecturing::AUDIENCE_PUBLIC,
        ]);

        $this->loginAsUser();
        $this->visitRoute('lecturings.show', $lecturing);
        $this->see($lecturing->lecturer_name);
    }

    /** @test */
    public function user_will_be_redirected_to_lecturing_detail_based_on_the_audience_code()
    {
        $publicLecturing = factory(Lecturing::class)->create([
            'audience_code' => Lecturing::AUDIENCE_PUBLIC,
        ]);

        $this->loginAsUser();
        $this->visitRoute('friday_lecturings.show', $publicLecturing);
        $this->seeRouteIs('lecturings.show', $publicLecturing);

        $fridayLecturing = factory(Lecturing::class)->create([
            'audience_code' => Lecturing::AUDIENCE_FRIDAY,
        ]);

        $this->visitRoute('lecturings.show', $fridayLecturing);
        $this->seeRouteIs('friday_lecturings.show', $fridayLecturing);
    }

    /** @test */
    public function user_can_see_lecturing_detail_in_friday_lecturing_show_page()
    {
        $lecturing = factory(Lecturing::class)->create([
            'audience_code' => Lecturing::AUDIENCE_FRIDAY,
        ]);

        $this->loginAsUser();
        $this->visitRoute('friday_lecturings.show', $lecturing);
        $this->seeText(__('lecturing.friday_lecturer_name'));
        $this->seeText($lecturing->lecturer_name);
        $this->seeText(__('lecturing.imam_name'));
        $this->seeText($lecturing->imam_name);
        $this->seeText(__('lecturing.muadzin_name'));
        $this->seeText($lecturing->muadzin_name);
    }
}
