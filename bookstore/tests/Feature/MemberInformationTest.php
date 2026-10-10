<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberInformationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_read_or_change_member_information(): void
    {
        $this->getJson(route('member-information.data'))->assertUnauthorized();
        $this->patchJson(route('member-information.update'), [])->assertUnauthorized();
        $this->patchJson(route('member-information.address.update'), [])->assertUnauthorized();
    }

    public function test_member_information_exposes_only_own_page_fields(): void
    {
        $user = User::factory()->create([
            'name' => 'First Last', 'first_name' => 'First', 'last_name' => 'Last', 'address' => 'Existing address',
        ]);

        $this->actingAs($user)->getJson(route('member-information.data'))
            ->assertOk()->assertExactJson([
                'member_number' => 'M-'.str_pad((string) $user->id, 5, '0', STR_PAD_LEFT),
                'name' => 'First Last', 'first_name' => 'First', 'last_name' => 'Last',
                'email' => $user->email, 'address' => 'Existing address',
            ]);
    }

    public function test_profile_changes_persist_and_ignore_another_user_id(): void
    {
        $user = User::factory()->create(['address' => 'Keep this address']);
        $other = User::factory()->create();
        $otherName = $other->name;

        $this->actingAs($user)->patchJson(route('member-information.update'), [
            'first_name' => 'New', 'last_name' => 'Member', 'email' => 'new-member@example.com',
            'user_id' => $other->id, 'password' => 'unexpected-change', 'address' => 'Not the address form',
        ])->assertOk()->assertJsonPath('name', 'New Member');

        $this->assertDatabaseHas('users', [
            'id' => $user->id, 'first_name' => 'New', 'last_name' => 'Member',
            'email' => 'new-member@example.com', 'address' => 'Keep this address',
        ]);
        $this->assertNull($user->fresh()->email_verified_at);
        $this->assertSame($otherName, $other->fresh()->name);
        $this->getJson(route('member-information.data'))->assertJsonPath('first_name', 'New');
    }

    public function test_unchanged_email_keeps_verification_and_other_email_is_rejected(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $this->actingAs($user)->patchJson(route('member-information.update'), [
            'first_name' => 'First', 'last_name' => 'Last', 'email' => $user->email,
        ])->assertOk();
        $this->assertNotNull($user->fresh()->email_verified_at);

        $this->patchJson(route('member-information.update'), [
            'first_name' => 'First', 'last_name' => 'Last', 'email' => $other->email,
        ])->assertUnprocessable()->assertJsonValidationErrors('email');
    }

    public function test_invalid_profile_does_not_change_stored_information(): void
    {
        $user = User::factory()->create();
        $name = $user->name;
        $this->actingAs($user)->patchJson(route('member-information.update'), [
            'first_name' => '', 'last_name' => '', 'email' => 'invalid',
        ])->assertUnprocessable()->assertJsonValidationErrors(['first_name', 'last_name', 'email']);
        $this->assertSame($name, $user->fresh()->name);
    }

    public function test_editing_delivery_updates_only_own_existing_address_field(): void
    {
        $user = User::factory()->create(['address' => 'Old']);
        $other = User::factory()->create(['address' => 'Other address']);
        $this->actingAs($user)->patchJson(route('member-information.address.update'), [
            'address' => 'Updated delivery address', 'user_id' => $other->id,
        ])->assertOk()->assertJsonPath('address', 'Updated delivery address');
        $this->assertSame('Updated delivery address', $user->fresh()->address);
        $this->assertSame('Other address', $other->fresh()->address);
        $this->patchJson(route('member-information.address.update'), ['address' => ''])
            ->assertUnprocessable()->assertJsonValidationErrors('address');
        $this->assertSame('Updated delivery address', $user->fresh()->address);
    }
}
