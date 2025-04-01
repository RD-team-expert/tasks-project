<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRequest;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $userService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userService = new UserService();
    }

    /** @test */
    public function it_creates_a_user()
    {
        $request = new UserRequest([
            'username' => 'john doe',
            'email' => 'john@example.com',
            'password' => '123123123',
            'email_verified_at' => now(),
            'role' => 'Employee',
        ]);

        $user = $this->userService->createUser($request);

        $this->assertDatabaseHas('users', [
            'username' => 'john_doe',
            'email' => 'john@example.com',
            'role' => 'Employee',
        ]);

        $this->assertTrue(Hash::check('secret123', $user->password));
    }

    /** @test */
    public function it_updates_a_user()
    {
        $user = User::factory()->create([
            'username' => 'jane_doe',
            'email' => 'jane@example.com',
            'role' => 'Employee',
        ]);

        $request = new UserRequest([
            'username' => 'jane_updated',
            'email' => 'jane_updated@example.com',
            'password' => 'newpassword',
            'role' => 'Manager',
        ]);

        $this->userService->updateUser($request, $user);

        $this->assertDatabaseHas('users', [
            'username' => 'jane_updated',
            'email' => 'jane_updated@example.com',
            'role' => 'Manager',
        ]);

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword', $user->password));
    }

    /** @test */
    public function it_deletes_a_user()
    {
        $user = User::factory()->create();

        $this->userService->deleteUser($user);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }

    /** @test */
    public function it_gets_employees_by_manager_group()
    {
        $manager = User::factory()->create(['role' => 'Manager']);
        $group = \App\Models\Group::factory()->create(['manager_id' => $manager->id]);

        $employee1 = User::factory()->create(['group_id' => $group->id, 'role' => 'Employee']);
        $employee2 = User::factory()->create(['group_id' => $group->id, 'role' => 'Employee']);
        $otherEmployee = User::factory()->create(['role' => 'Employee']);

        $employees = $this->userService->getEmployeesByManagerGroup($manager);

        $this->assertCount(2, $employees);
        $this->assertTrue($employees->contains($employee1));
        $this->assertTrue($employees->contains($employee2));
        $this->assertFalse($employees->contains($otherEmployee));
    }
}
