<?php

namespace Tests\Unit;

use App\DTO\LoginLogDTO;
use App\DTO\UserDTO;
use App\Models\LoginLog;
use App\Models\User;
use App\Repository\LoginLogRepositoryEloquent;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginLogRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private LoginLogRepositoryEloquent $repository;
    private User $testUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new LoginLogRepositoryEloquent();
        
        // Create test user
        $this->testUser = User::create([
            'username' => 'testuser',
            'password' => Hash::make('TestPassword123456!'),
            'fullname' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'admin',
        ]);
    }

    public function test_can_insert_login_log()
    {
        $dto = new LoginLogDTO();
        $dto->userId = $this->testUser->id;
        $dto->ipAddress = '127.0.0.1';
        $dto->usernameAttempt = 'test@example.com';
        $dto->deviceInfo = 'Mozilla/5.0';
        $dto->loginAt = Carbon::now();
        $dto->isSuccess = true;
        $dto->failureReason = null;

        $result = $this->repository->insert($dto);

        $this->assertNotNull($result);
        $this->assertNotNull($result->id);
        $this->assertEquals($this->testUser->id, $result->userId);
        $this->assertEquals('127.0.0.1', $result->ipAddress);
        $this->assertEquals(true, $result->isSuccess);
        $this->assertDatabaseHas('login_logs', [
            'user_id' => $this->testUser->id,
            'email_attempt' => 'test@example.com',
            'status' => 'success',
        ]);
    }

    public function test_can_get_all_login_logs()
    {
        // Create test login logs
        LoginLog::create([
            'user_id' => $this->testUser->id,
            'ip_address' => '127.0.0.1',
            'email_attempt' => 'test@example.com',
            'device_info' => 'Mozilla/5.0',
            'login_at' => Carbon::now(),
            'status' => 'success',
        ]);

        LoginLog::create([
            'user_id' => $this->testUser->id,
            'ip_address' => '192.168.1.1',
            'email_attempt' => 'test@example.com',
            'device_info' => 'Chrome/91.0',
            'login_at' => Carbon::now()->subMinutes(5),
            'status' => 'failed',
            'failure_reason' => 'Invalid password',
        ]);

        $results = $this->repository->getAll();

        $this->assertIsArray($results);
        $this->assertCount(2, $results);
        $this->assertInstanceOf(LoginLogDTO::class, $results[0]);
    }

    public function test_can_get_login_log_by_id()
    {
        $loginLog = LoginLog::create([
            'user_id' => $this->testUser->id,
            'ip_address' => '127.0.0.1',
            'email_attempt' => 'test@example.com',
            'device_info' => 'Mozilla/5.0',
            'login_at' => Carbon::now(),
            'status' => 'success',
        ]);

        $result = $this->repository->getById($loginLog->id);

        $this->assertNotNull($result);
        $this->assertEquals($loginLog->id, $result->id);
        $this->assertEquals('127.0.0.1', $result->ipAddress);
    }

    public function test_can_get_login_logs_by_user_id()
    {
        // Create another user
        $anotherUser = User::create([
            'username' => 'anotheruser',
            'password' => Hash::make('AnotherPassword123!'),
            'fullname' => 'Another User',
            'email' => 'another@example.com',
            'role' => 'doctor',
        ]);

        // Create login logs for both users
        LoginLog::create([
            'user_id' => $this->testUser->id,
            'ip_address' => '127.0.0.1',
            'email_attempt' => 'test@example.com',
            'device_info' => 'Mozilla/5.0',
            'login_at' => Carbon::now(),
            'status' => 'success',
        ]);

        LoginLog::create([
            'user_id' => $anotherUser->id,
            'ip_address' => '192.168.1.1',
            'email_attempt' => 'another@example.com',
            'device_info' => 'Chrome/91.0',
            'login_at' => Carbon::now(),
            'status' => 'success',
        ]);

        $results = $this->repository->getByUserId($this->testUser->id);

        $this->assertIsArray($results);
        $this->assertCount(1, $results);
        $this->assertEquals($this->testUser->id, $results[0]->userId);
    }

    public function test_can_get_successful_logins()
    {
        LoginLog::create([
            'user_id' => $this->testUser->id,
            'ip_address' => '127.0.0.1',
            'email_attempt' => 'test@example.com',
            'device_info' => 'Mozilla/5.0',
            'login_at' => Carbon::now(),
            'status' => 'success',
        ]);

        LoginLog::create([
            'user_id' => $this->testUser->id,
            'ip_address' => '127.0.0.1',
            'email_attempt' => 'test@example.com',
            'device_info' => 'Mozilla/5.0',
            'login_at' => Carbon::now()->subMinutes(5),
            'status' => 'failed',
            'failure_reason' => 'Invalid password',
        ]);

        $results = $this->repository->getSuccessfulLogins();

        $this->assertIsArray($results);
        $this->assertCount(1, $results);
        $this->assertEquals('success', $results[0]->status);
    }

    public function test_can_get_failed_logins()
    {
        LoginLog::create([
            'user_id' => $this->testUser->id,
            'ip_address' => '127.0.0.1',
            'email_attempt' => 'test@example.com',
            'device_info' => 'Mozilla/5.0',
            'login_at' => Carbon::now(),
            'status' => 'success',
        ]);

        LoginLog::create([
            'user_id' => $this->testUser->id,
            'ip_address' => '127.0.0.1',
            'email_attempt' => 'test@example.com',
            'device_info' => 'Mozilla/5.0',
            'login_at' => Carbon::now()->subMinutes(5),
            'status' => 'failed',
            'failure_reason' => 'Invalid password',
        ]);

        $results = $this->repository->getFailedLogins();

        $this->assertIsArray($results);
        $this->assertCount(1, $results);
        $this->assertEquals('failed', $results[0]->status);
        $this->assertNotNull($results[0]->failureReason);
    }

    public function test_can_get_failed_logins_by_email_within_time_window()
    {
        // Create failed login within time window (5 minutes ago)
        LoginLog::create([
            'user_id' => null,
            'ip_address' => '127.0.0.1',
            'email_attempt' => 'test@example.com',
            'device_info' => 'Mozilla/5.0',
            'login_at' => Carbon::now()->subMinutes(5),
            'status' => 'failed',
            'failure_reason' => 'Invalid password',
        ]);

        // Create failed login outside time window (20 minutes ago)
        LoginLog::create([
            'user_id' => null,
            'ip_address' => '127.0.0.1',
            'email_attempt' => 'test@example.com',
            'device_info' => 'Mozilla/5.0',
            'login_at' => Carbon::now()->subMinutes(20),
            'status' => 'failed',
            'failure_reason' => 'Invalid password',
        ]);

        // Create successful login (should be excluded)
        LoginLog::create([
            'user_id' => $this->testUser->id,
            'ip_address' => '127.0.0.1',
            'email_attempt' => 'test@example.com',
            'device_info' => 'Mozilla/5.0',
            'login_at' => Carbon::now()->subMinutes(3),
            'status' => 'success',
        ]);

        $results = $this->repository->getFailedLoginsByEmail('test@example.com', 15);

        $this->assertIsArray($results);
        $this->assertCount(1, $results);
        $this->assertEquals('failed', $results[0]->status);
    }

    public function test_can_get_recent_logins_by_user_id()
    {
        // Create 15 login logs
        for ($i = 0; $i < 15; $i++) {
            LoginLog::create([
                'user_id' => $this->testUser->id,
                'ip_address' => '127.0.0.1',
                'email_attempt' => 'test@example.com',
                'device_info' => 'Mozilla/5.0',
                'login_at' => Carbon::now()->subMinutes($i),
                'status' => 'success',
            ]);
        }

        $results = $this->repository->getRecentLoginsByUserId($this->testUser->id, 10);

        $this->assertIsArray($results);
        $this->assertCount(10, $results);
    }

    public function test_can_delete_login_log()
    {
        $loginLog = LoginLog::create([
            'user_id' => $this->testUser->id,
            'ip_address' => '127.0.0.1',
            'email_attempt' => 'test@example.com',
            'device_info' => 'Mozilla/5.0',
            'login_at' => Carbon::now(),
            'status' => 'success',
        ]);

        $result = $this->repository->delete($loginLog->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('login_logs', [
            'id' => $loginLog->id,
        ]);
    }

    public function test_delete_returns_false_for_non_existent_id()
    {
        $result = $this->repository->delete(99999);

        $this->assertFalse($result);
    }

    public function test_can_get_latest_id()
    {
        LoginLog::create([
            'user_id' => $this->testUser->id,
            'ip_address' => '127.0.0.1',
            'email_attempt' => 'test@example.com',
            'device_info' => 'Mozilla/5.0',
            'login_at' => Carbon::now(),
            'status' => 'success',
        ]);

        $latestId = $this->repository->getLatestId();

        $this->assertGreaterThan(0, $latestId);
    }

    public function test_get_latest_id_returns_zero_when_no_records()
    {
        $latestId = $this->repository->getLatestId();

        $this->assertEquals(0, $latestId);
    }

    public function test_get_by_id_returns_null_for_non_existent_id()
    {
        $result = $this->repository->getById(99999);

        $this->assertNull($result);
    }
}
