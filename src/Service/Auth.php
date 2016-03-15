<?php
namespace Service;

use Core\Message;
use Core\Base;
use Security\BanManager;
use Security\Bcrypt;

class Auth extends Base
{

    public function isLoggedIn()
    {
        // -- No session, bad session, or session expired
        if (empty($_SESSION['uid']) || $_SESSION['ip'] != $this->allIPs() || $_SESSION['expiresOn'] <= time()) {
            $this->logout();
            return false;
        }
        // -- Logged: increase session duration and return true
        // Keep connected
        if (isset($_SESSION['keepConnected']) && $_SESSION['keepConnected']) {
            $_SESSION['expiresOn'] = time() + SessionTimeoutKeepConnected;
        }
        // Normal (need activites in a SessionTimeoutNormal hours interval, and no browser closed, to stay connected)
        else {
            $_SESSION['expiresOn'] = time() + SessionTimeoutNormal;
        }
        return true;
    }

    public function login()
    {
        // -- Security key filled
        if ((isset($_POST['securityKey']) && NULL != $_POST['securityKey'] && '' != $_POST['securityKey'])) {
            // - Is banished
            $banManager = new BanManager(CACHE_PATH . '/ban.json', LoginTryNumber, LoginBanishedTimeout);
            if ($banManager->isBannished()) {
                $this->response->addFlash('Trop d\'erreurs consécutives, vous voilà banni pour quelques temps ! Si vous avez un problème, vous savez qui contacter, non ?', ERREUR);
                return false;
            }
            
            $securityKey = parserS(trim($_POST['securityKey']));
            $passwordChecker = new Bcrypt();
            // - Password ok: save it and continue
            if ($passwordChecker->verify($securityKey, SecurityKey)) {
                // Signal success to ban management
                $banManager->resetIp();
                // Store this in session
                $_SESSION['uid'] = sha1(uniqid('', true) . '_' . mt_rand()); // generate unique random number (different than phpsessionid)
                $_SESSION['ip'] = $this->allIPs(); // We store IP address(es) of the client to make sure session is not hijacked.
                                                   // $_SESSION['securityKey'] = SecurityKey;
                $_SESSION['role'] = 'Administrateur';
                // Standard session expiration (=when browser closes)
                $_SESSION['expiresOn'] = time() + SessionTimeoutNormal;
                $cookieExpirationTimeout = 0; // 0 means "When browser closes"
                                              // Keep connected
                if (isset($_POST['keepConnected']) && parserI($_POST['keepConnected'])) {
                    $cookieExpirationTimeout = SessionTimeoutKeepConnected;
                    $_SESSION['keepConnected'] = true;
                    $_SESSION['expiresOn'] = time() + SessionTimeoutKeepConnected;
                }
                // Set session cookie expiration on client side and Send cookie with new expiration date to browser.
                // Note: Never forget the trailing slash on the cookie path !
                session_set_cookie_params($cookieExpirationTimeout, dirname($_SERVER["SCRIPT_NAME"]) . '/', $_SERVER['HTTP_HOST']);
                // session_regenerate_id(true);
                // -- Continue after prefilter
                return true;
            }
            // - Password error
            $banManager->addFaillure();
            $this->response->addFlash('Arg, mot de passe incorrect.', ERREUR);
        }
        return false;
    }

    public function logout()
    {
        if (isset($_SESSION)) {
            unset($_SESSION['uid']);
            unset($_SESSION['ip']);
            unset($_SESSION['role']);
        }
    }

    /**
     * Returns the IP address of the client (Used to prevent session cookie hijacking.)
     * From Shaarli: http://sebsauvage.net/wiki/doku.php?id=php:shaarli
     * Licence: http://www.opensource.org/licenses/zlib-license.php
     */
    private function allIPs()
    {
        $ip = $_SERVER["REMOTE_ADDR"];
        // Then we use more HTTP headers to prevent session hijacking from users behind the same proxy.
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $ip . '_' . $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $ip . '_' . $_SERVER['HTTP_CLIENT_IP'];
        }
        return $ip;
    }
}
