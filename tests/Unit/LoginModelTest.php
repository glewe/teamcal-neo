<?php
namespace App\Models;

/**
 * Mock setcookie to prevent "headers already sent" errors and capture cookie setting
 */
function setcookie($name, $value = "", $expire = 0, $path = "", $domain = "", $secure = false, $httponly = false) {
    return true;
}

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\LoginModel;
use App\Models\ConfigModel;
use App\Models\UserModel;
use App\Models\UserOptionModel;
use App\Services\LdapService;

class LoginModelTest extends TestCase
{
    private $configMock;
    private $ldapMock;
    private $userMock;
    private $userOptionMock;

    protected function setUp(): void
    {
        $this->configMock = $this->createMock(ConfigModel::class);
        $this->ldapMock = $this->createMock(LdapService::class);
        $this->userMock = $this->createMock(UserModel::class);
        $this->userOptionMock = $this->createMock(UserOptionModel::class);

        // Setup globals used by LoginModel
        $GLOBALS['U'] = $this->userMock;
        $GLOBALS['C'] = $this->configMock;
        $GLOBALS['UO'] = $this->userOptionMock;
        $GLOBALS['CONF'] = ['db_table_log' => 'log_table'];
        $_SERVER['PHP_SELF'] = 'index.php';
        $_SERVER['HTTP_HOST'] = 'localhost';
    }

    public function testLoginUserCallsLdapVerifyForNormalUser()
    {
        $username = 'testuser';
        $password = 'password';

        // Mock User finding logic
        $this->userMock->method('findByName')->willReturn(true);
        $this->userMock->username = $username;
        $this->userMock->password = 'hashedpassword'; // Not used for LDAP but good to have
        $this->userMock->locked = 0;
        $this->userMock->onhold = 0;

        // Mock UO read for verifying code
        $this->userOptionMock->method('read')->willReturn(false);

        // Expect LdapService verify to be called
        $this->ldapMock->expects($this->once())
             ->method('verify')
             ->with($username, $password)
             ->willReturn(0); // Success

        // Also config read needed for cookie lifetime
        $this->configMock->method('read')->willReturn('3600');

        // Mock User update (called on successful login)
        $this->userMock->expects($this->once())->method('update');

        $loginModel = new LoginModel($this->configMock, [], $this->ldapMock);

        $result = $loginModel->loginUser($username, $password);

        $this->assertEquals(0, $result);
    }

    public function testLoginUserAdminSkippsLdap()
    {
        // "admin" user should skip LDAP per logic in LoginModel
        // but wait, logic says: if ($ldap_yes && $loginname != "admin")
        // So for "admin", it goes to localVerify.

        $username = 'admin';
        $password = 'password';
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Mock User finding logic
        $this->userMock->method('findByName')->willReturn(true);
        $this->userMock->username = $username;
        $this->userMock->password = $hashedPassword;
        $this->userMock->locked = 0;
        $this->userMock->onhold = 0;

        $this->userOptionMock->method('read')->willReturn(false);

        // Expect LdapService verify NOT to be called
        $this->ldapMock->expects($this->never())->method('verify');

        // Config for cookies
        $this->configMock->method('read')->willReturn('3600');

        $this->userMock->expects($this->once())->method('update');

        $loginModel = new LoginModel($this->configMock, [], $this->ldapMock);

        $result = $loginModel->loginUser($username, $password);

        $this->assertEquals(0, $result);
    }
}
