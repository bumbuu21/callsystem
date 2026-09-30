<?php

namespace Tests\Feature;

use App\Models\Agent;
use App\Models\Call;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_register_and_see_dashboard(): void
    {
        $response = $this->post('/burtguuleh', [
            'name' => 'Бат Болд',
            'email' => 'bat@example.test',
            'username' => 'batbold',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', [
            'username' => 'batbold',
            'role' => 'customer',
            'status' => 'active',
        ]);
    }

    public function test_active_user_can_login_with_username(): void
    {
        $user = User::factory()->create([
            'username' => 'operator1',
            'status' => 'active',
        ]);

        $response = $this->post('/nevtreh', [
            'login' => 'operator1',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_customer_can_submit_a_call(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($user)->post('/duudlaga', [
            'call_type' => 'hardware',
            'call_from' => 'Баянгол дүүрэг',
            'description' => 'Принтер хэвлэхгүй байна.',
        ]);

        $response->assertRedirect('/duudlaga/1');
        $this->assertDatabaseHas('calls', [
            'user_id' => $user->id,
            'status' => 'submitted',
            'call_type' => 'hardware',
        ]);
    }

    public function test_customer_sees_their_calls_in_the_list(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        Call::create([
            'caller_name' => $user->name,
            'call_type' => 'program',
            'call_from' => 'Чингэлтэй дүүрэг',
            'description' => 'Систем нээгдэхгүй байна.',
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)->get('/duudlaguud')
            ->assertOk()
            ->assertSee('Чингэлтэй дүүрэг')
            ->assertSee('Илгээгдсэн');
    }

    public function test_operator_assigns_and_engineer_resolves_a_call(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $operator = User::factory()->create(['role' => 'operator']);
        $engineerUser = User::factory()->create(['role' => 'agent']);
        $agent = Agent::create(['user_id' => $engineerUser->id, 'name' => $engineerUser->name]);
        $call = Call::create([
            'caller_name' => $customer->name,
            'call_type' => 'network',
            'call_from' => 'Сүхбаатар дүүрэг',
            'description' => 'Сүлжээ тасарсан.',
            'user_id' => $customer->id,
        ]);

        $this->actingAs($operator)->patch("/duudlaga/{$call->id}/huvaariulah", ['agent_id' => $agent->id])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('calls', ['id' => $call->id, 'agent_id' => $agent->id, 'status' => 'accepted']);

        $this->actingAs($engineerUser)->patch("/duudlaga/{$call->id}/shiideh", [
            'agent_description' => 'Сүлжээний төхөөрөмжийг дахин асааж, холболтыг сэргээв.',
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('calls', ['id' => $call->id, 'status' => 'resolved']);
    }

    public function test_customer_can_comment_on_resolved_call(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $call = Call::create([
            'caller_name' => $user->name,
            'call_type' => 'other',
            'call_from' => 'Төв байр',
            'description' => 'Тусламж хэрэгтэй.',
            'user_id' => $user->id,
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);

        $this->actingAs($user)->patch("/duudlaga/{$call->id}/setgegdel", [
            'client_comment' => 'Шуурхай шийдсэнд баярлалаа.',
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('calls', ['id' => $call->id, 'client_comment' => 'Шуурхай шийдсэнд баярлалаа.']);
    }

    public function test_admin_can_view_reports_and_deactivate_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer', 'status' => 'active']);
        Call::create([
            'caller_name' => $customer->name,
            'call_type' => 'network',
            'call_from' => 'Төв серверийн өрөө',
            'description' => 'Холболт тасарсан.',
            'user_id' => $customer->id,
        ]);

        $this->actingAs($admin)->get('/admin/duudlaguud?call_type=network')
            ->assertOk()
            ->assertSee('Төв серверийн өрөө');

        $this->actingAs($admin)->get('/admin/duudlaguud/csv?call_type=network')
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $this->actingAs($admin)->patch("/admin/hereglegchid/{$customer->id}/tolov")
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', ['id' => $customer->id, 'status' => 'inactive']);
    }

    public function test_admin_can_create_an_engineer_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post('/admin/hereglegchid', [
            'name' => 'Шинэ инженер', 'email' => 'new-engineer@example.test', 'username' => 'newengineer',
            'phone' => '99110000', 'role' => 'agent', 'title' => 'Сүлжээний инженер',
            'password' => 'password123', 'password_confirmation' => 'password123',
        ])->assertRedirect('/admin/hereglegchid');

        $this->assertDatabaseHas('users', ['username' => 'newengineer', 'role' => 'agent']);
        $this->assertDatabaseHas('agents', ['name' => 'Шинэ инженер', 'title' => 'Сүлжээний инженер']);
    }
}
