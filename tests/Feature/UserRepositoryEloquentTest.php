<?php

namespace Tests\Feature;

use App\DTO\UserDTO;
use App\Repository\UserRepositoryEloquent;
use App\Type\UserRole;
use Carbon\CarbonInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserRepositoryEloquentTest extends TestCase
{
    use RefreshDatabase;

    protected UserRepositoryEloquent $userRepository;

    protected array $userData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = new UserRepositoryEloquent;

        $this->userData = [
            'username' => 'johndoe',
            'password' => 'password123',
            'fullname' => 'John Doe',
            'email' => 'john.doe@example.com',
            'role' => UserRole::DOCTOR,
        ];
    }

    // test untuk insert user
    public function test_insert_user_success(): void
    {
        $userDTO = new UserDTO;
        $userDTO->username = $this->userData['username'];
        $userDTO->password = Hash::make($this->userData['password']);
        $userDTO->fullname = $this->userData['fullname'];
        $userDTO->email = $this->userData['email'];
        $userDTO->role = $this->userData['role'];

        $insertedUserDTO = $this->userRepository->insert($userDTO);

        $this->assertNotNull($insertedUserDTO);
        $this->assertEquals($this->userData['username'], $insertedUserDTO->username);
        $this->assertEquals($this->userData['fullname'], $insertedUserDTO->fullname);
        $this->assertEquals($this->userData['email'], $insertedUserDTO->email);
        $this->assertEquals($this->userData['role'], $insertedUserDTO->role);
        $this->assertInstanceOf(UserDTO::class, $insertedUserDTO);
        $this->assertInstanceOf(CarbonInterface::class, $insertedUserDTO->createdAt);
        $this->assertInstanceOf(CarbonInterface::class, $insertedUserDTO->updatedAt);

        // verifikasi tipe semua data sesuai dengan dto
        $this->assertIsString($insertedUserDTO->id);
        $this->assertIsString($insertedUserDTO->username);
        $this->assertIsString($insertedUserDTO->fullname);
        $this->assertIsString($insertedUserDTO->email);
    }

    // test untuk insert user gagal ketika email sudah ada
    public function test_insert_user_failed_when_email_exists(): void
    {
        $userDTO = new UserDTO;
        $userDTO->username = $this->userData['username'];
        $userDTO->password = Hash::make($this->userData['password']);
        $userDTO->fullname = $this->userData['fullname'];
        $userDTO->email = $this->userData['email'];
        $userDTO->role = $this->userData['role'];

        $firstInsert = $this->userRepository->insert($userDTO);
        $this->assertNotNull($firstInsert);

        $duplicateUserDTO = new UserDTO;
        $duplicateUserDTO->username = 'janedoe';
        $duplicateUserDTO->password = Hash::make('password456');
        $duplicateUserDTO->fullname = 'Jane Doe';
        $duplicateUserDTO->email = $this->userData['email']; // Same email
        $duplicateUserDTO->role = UserRole::ADMIN;

        $this->expectException(\Illuminate\Database\QueryException::class);
        $secondInsert = $this->userRepository->insert($duplicateUserDTO);
        $this->assertNull($secondInsert);
        // verifikasi tipe semua data sesuai dengan dto
        $this->assertIsString($firstInsert->id);
        $this->assertIsString($firstInsert->username);
        $this->assertIsString($firstInsert->fullname);
        $this->assertIsString($firstInsert->password);
        $this->assertIsString($firstInsert->email);
        $this->assertIsString($firstInsert->role);
        $this->assertInstanceOf(CarbonInterface::class, $firstInsert->createdAt);
        $this->assertInstanceOf(CarbonInterface::class, $firstInsert->updatedAt);
    }

    // test untuk insert user gagal ketika email sudah ada
    public function test_insert_user_failed_when_exists(): void
    {
        $userDTO = new UserDTO;
        $userDTO->username = $this->userData['username'];
        $userDTO->password = Hash::make($this->userData['password']);
        $userDTO->fullname = $this->userData['fullname'];
        $userDTO->email = $this->userData['email'];
        $userDTO->role = $this->userData['role'];

        $firstInsert = $this->userRepository->insert($userDTO);
        $this->assertNotNull($firstInsert);

        $duplicateUserDTO = new UserDTO;
        $duplicateUserDTO->username = $this->userData['username']; // Same username
        $duplicateUserDTO->password = Hash::make('password456');
        $duplicateUserDTO->fullname = 'Jane Doe';
        $duplicateUserDTO->email = 'anjing@gmail.com';
        $duplicateUserDTO->role = UserRole::ADMIN;

        $this->expectException(\Illuminate\Database\QueryException::class);
        $secondInsert = $this->userRepository->insert($duplicateUserDTO);
        $this->assertNull($secondInsert);
    }

    // test untuk get all users
    public function test_get_all_users(): void
    {
        // Insert some users first
        for ($i = 1; $i <= 3; $i++) {
            $userDTO = new UserDTO;
            $userDTO->username = "user$i";
            $userDTO->password = Hash::make("password$i");
            $userDTO->fullname = "User $i";
            $userDTO->email = "user$i@example.com";
            $userDTO->role = ($i % 2 == 0) ? UserRole::ADMIN : UserRole::DOCTOR;

            $this->userRepository->insert($userDTO);
        }

        $users = $this->userRepository->getAll();
        $this->assertIsArray($users);
        $this->assertCount(3, $users);
        $this->assertInstanceOf(UserDTO::class, $users[0]);
        // verifikasi tipe semua data sesuai dengan dto
        foreach ($users as $user) {
            $this->assertIsString($user->id);
            $this->assertIsString($user->username);
            $this->assertIsString($user->fullname);
            $this->assertIsString($user->password);
            $this->assertIsString($user->email);
            $this->assertInstanceOf(UserRole::class, $user->role);
            $this->assertInstanceOf(CarbonInterface::class, $user->createdAt);
            $this->assertInstanceOf(CarbonInterface::class, $user->updatedAt);
        }

    }

    // test untuk getall users ketika tidak ada data
    public function test_get_all_users_when_no_data(): void
    {
        $users = $this->userRepository->getAll();
        $this->assertIsArray($users);
        $this->assertCount(0, $users);
    }

    // test untuk edit user
    public function test_edit_user(): void
    {
        $userDTO = new UserDTO;
        $userDTO->username = $this->userData['username'];
        $userDTO->password = Hash::make($this->userData['password']);
        $userDTO->fullname = $this->userData['fullname'];
        $userDTO->email = $this->userData['email'];
        $userDTO->role = $this->userData['role'];

        $insertedUserDTO = $this->userRepository->insert($userDTO);
        $this->assertNotNull($insertedUserDTO);

        $insertedUserId = $insertedUserDTO->id;
        $updatedUserDTO = new UserDTO;
        $updatedUserDTO->fullname = 'Updated Name';
        $updatedUserDTO->role = UserRole::ADMIN;

        $editedUserDTO = $this->userRepository->edit($insertedUserId, $updatedUserDTO);
        $this->assertNotNull($editedUserDTO);
        $this->assertEquals('Updated Name', $editedUserDTO->fullname);
        $this->assertEquals(UserRole::ADMIN, $editedUserDTO->role);
        $this->assertInstanceOf(UserDTO::class, $editedUserDTO);
    }

    // test untuk edit user yang tidak ada
    public function test_edit_non_existent_user(): void
    {
        $nonExistentUserId = '9999';
        $updatedUserDTO = new UserDTO;
        $updatedUserDTO->fullname = 'Updated Name';
        $updatedUserDTO->email = 'updated@example.com';

        $editedUserDTO = $this->userRepository->edit($nonExistentUserId, $updatedUserDTO);
        $this->assertNull($editedUserDTO);
    }

    // test untuk edit user dengan data kosong
    public function test_edit_user_with_empty_data(): void
    {
        $userDTO = new UserDTO;
        $userDTO->username = $this->userData['username'];
        $userDTO->password = Hash::make($this->userData['password']);
        $userDTO->fullname = $this->userData['fullname'];
        $userDTO->email = $this->userData['email'];
        $userDTO->role = $this->userData['role'];

        $insertedUserDTO = $this->userRepository->insert($userDTO);
        $this->assertNotNull($insertedUserDTO);

        $insertedUserId = $insertedUserDTO->id;
        $updatedUserDTO = new UserDTO; // Data kosong

        $editedUserDTO = $this->userRepository->edit($insertedUserId, $updatedUserDTO);
        $this->assertNull($editedUserDTO);
    }

    // test untuk edit user dengan data yang tidak bisa diedit seperti username dan password
    public function test_edit_user_with_non_editable_data(): void
    {
        $userDTO = new UserDTO;
        $userDTO->username = $this->userData['username'];
        $userDTO->password = Hash::make($this->userData['password']);
        $userDTO->fullname = $this->userData['fullname'];
        $userDTO->email = $this->userData['email'];
        $userDTO->role = $this->userData['role'];

        $insertedUserDTO = $this->userRepository->insert($userDTO);
        $this->assertNotNull($insertedUserDTO);

        $insertedUserId = $insertedUserDTO->id;
        $updatedUserDTO = new UserDTO; // Data kosong
        $updatedUserDTO->username = 'newusername'; // tidak bisa diedit
        $updatedUserDTO->password = Hash::make('newpassword'); // tidak bisa died

        $editedUserDTO = $this->userRepository->edit($insertedUserId, $updatedUserDTO);
        $this->assertNotNull($editedUserDTO);

        $this->assertEquals($this->userData['username'], $editedUserDTO->username); // tetap sama
        $this->assertEquals($this->userData['email'], $editedUserDTO->email); // tetap sama
        $this->assertInstanceOf(UserDTO::class, $editedUserDTO);

    }

    // test getById user
    public function test_get_by_id_user(): void
    {
        $userDTO = new UserDTO;
        $userDTO->username = $this->userData['username'];
        $userDTO->password = Hash::make($this->userData['password']);
        $userDTO->fullname = $this->userData['fullname'];
        $userDTO->email = $this->userData['email'];
        $userDTO->role = $this->userData['role'];

        $insertedUserDTO = $this->userRepository->insert($userDTO);
        $this->assertNotNull($insertedUserDTO);

        $insertedUserId = $insertedUserDTO->id;
        $fetchedUserDTO = $this->userRepository->getById($insertedUserId);

        $this->assertNotNull($fetchedUserDTO);
        $this->assertEquals($insertedUserDTO->username, $fetchedUserDTO->username);
        $this->assertEquals($insertedUserDTO->fullname, $fetchedUserDTO->fullname);
        $this->assertEquals($insertedUserDTO->email, $fetchedUserDTO->email);
        $this->assertInstanceOf(UserDTO::class, $fetchedUserDTO);

        $this->assertIsString($fetchedUserDTO->id);
        $this->assertIsString($fetchedUserDTO->username);
        $this->assertIsString($fetchedUserDTO->fullname);
        $this->assertIsString($fetchedUserDTO->password);
        $this->assertIsString($fetchedUserDTO->email);
        $this->assertInstanceOf(UserRole::class, $fetchedUserDTO->role);
        $this->assertInstanceOf(CarbonInterface::class, $fetchedUserDTO->createdAt);
        $this->assertInstanceOf(CarbonInterface::class, $fetchedUserDTO->updatedAt);

    }

    // test getById user yang tidak ada
    public function test_get_by_id_non_existent_user(): void
    {
        $nonExistentUserId = '9999';
        $fetchedUserDTO = $this->userRepository->getById($nonExistentUserId);
        $this->assertNull($fetchedUserDTO);
    }

    // test getByEmail user
    public function test_get_by_email_user(): void
    {
        $userDTO = new UserDTO;
        $userDTO->username = $this->userData['username'];
        $userDTO->password = Hash::make($this->userData['password']);
        $userDTO->fullname = $this->userData['fullname'];
        $userDTO->email = $this->userData['email'];
        $userDTO->role = $this->userData['role'];

        $insertedUserDTO = $this->userRepository->insert($userDTO);
        $this->assertNotNull($insertedUserDTO);

        $fetchedUserDTO = $this->userRepository->getByEmail($this->userData['email']);

        $this->assertNotNull($fetchedUserDTO);
        $this->assertEquals($insertedUserDTO->username, $fetchedUserDTO->username);
        $this->assertEquals($insertedUserDTO->fullname, $fetchedUserDTO->fullname);
        $this->assertEquals($insertedUserDTO->email, $fetchedUserDTO->email);
        $this->assertInstanceOf(UserDTO::class, $fetchedUserDTO);

        $this->assertIsString($fetchedUserDTO->id);
        $this->assertIsString($fetchedUserDTO->username);
        $this->assertIsString($fetchedUserDTO->fullname);
        $this->assertIsString($fetchedUserDTO->password);
        $this->assertIsString($fetchedUserDTO->email);
        $this->assertInstanceOf(UserRole::class, $fetchedUserDTO->role);
        $this->assertInstanceOf(CarbonInterface::class, $fetchedUserDTO->createdAt);
        $this->assertInstanceOf(CarbonInterface::class, $fetchedUserDTO->updatedAt);
    }

    // test getByEmail user yang tidak ada
    public function test_get_by_email_non_existent_user(): void
    {
        $nonExistentEmail = 'notfound@example.com';
        $fetchedUserDTO = $this->userRepository->getByEmail($nonExistentEmail);
        $this->assertNull($fetchedUserDTO);
    }

    // test getLatestId user
    public function test_get_latest_id_user(): void
    {
        // Insert some users first
        $latestInsertedId = null;
        for ($i = 1; $i <= 4; $i++) {
            $userDTO = new UserDTO;
            $userDTO->username = "user$i";
            $userDTO->password = Hash::make("password$i");
            $userDTO->fullname = "User $i";
            $userDTO->email = "user$i@example.com";
            $userDTO->role = UserRole::DOCTOR;

            $insertedUserDTO = $this->userRepository->insert($userDTO);
            $latestInsertedId = $insertedUserDTO->id;
        }

        $latestId = $this->userRepository->getLatestId();
        $this->assertEquals($latestInsertedId, $latestId);
    }

    // test getLatestId user ketika tidak ada data
    public function test_get_latest_id_user_when_no_data(): void
    {
        $latestId = $this->userRepository->getLatestId();
        $this->assertEquals(0, $latestId);
    }

    // test delete user
    public function test_delete_user(): void
    {
        $userDTO = new UserDTO;
        $userDTO->username = $this->userData['username'];
        $userDTO->password = Hash::make($this->userData['password']);
        $userDTO->fullname = $this->userData['fullname'];
        $userDTO->email = $this->userData['email'];
        $userDTO->role = $this->userData['role'];

        $insertedUserDTO = $this->userRepository->insert($userDTO);
        $this->assertNotNull($insertedUserDTO);

        $insertedUserId = $insertedUserDTO->id;
        $deleteResult = $this->userRepository->delete($insertedUserId);
        $this->assertTrue($deleteResult);

        // Verify user is deleted
        $fetchedUserDTO = $this->userRepository->getById($insertedUserId);
        $this->assertNull($fetchedUserDTO);
    }

    // test delete user yang tidak ada
    public function test_delete_non_existent_user(): void
    {
        $nonExistentUserId = '9999';
        $deleteResult = $this->userRepository->delete($nonExistentUserId);
        $this->assertFalse($deleteResult);
    }
}
