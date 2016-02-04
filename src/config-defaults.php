<?php

// Names
defined('SiteNom') or define('SiteNom', 'Le CEP Saint-Maur');
defined('SiteDesc') or define('SiteDesc', 'Communauté Évangélique Protestante');
defined('Encodage') or define('Encodage', 'UTF-8');
defined('DefaultLocale') or define('DefaultLocale', 'fr_FR');
defined('Author') or define('Author', 'Vincent et Olivier');
defined('MetaTitleDefault') or define('MetaTitleDefault', SiteNom . ' - ' . SiteDesc);
defined('MetaKwDefault') or define('MetaKwDefault', 'Dieu, église, protestant, évangélique, cep, saint-maur');
defined('MetaDescDefault') or define('MetaDescDefault', SiteDesc . ' de Saint-Maur');
defined('MetaTitle') or define('MetaTitle', SiteNom);
defined('MetaKw') or define('MetaKw', 'Dieu, église, cep, saint-maur');
defined('MetaDesc') or define('MetaDesc', SiteNom);

// Database
defined('BDD_HOST') or define("BDD_HOST", 'localhost');
defined('BDD_USER') or define("BDD_USER", 'root');
defined('BDD_MDP') or define("BDD_MDP", 'root');
defined('BDD_NAME') or define("BDD_NAME", 'cepsaintmaur');
defined('PREFIXE_DB') or define('PREFIXE_DB', 'cep_');

// Path constants
// Convention : on ne termine jamais terminé par un slash !
defined('SITE_PATH') or define('SITE_PATH', 'http://cepsaintmaur.fr/');
defined('POSITION_RELATIVE') or define('POSITION_RELATIVE', '');
defined('DATA_PATH') or define('DATA_PATH', POSITION_RELATIVE . 'app');
defined('CACHE_PATH') or define('CACHE_PATH', POSITION_RELATIVE . 'cache');
defined('INCLUDE_PATH') or define('INCLUDE_PATH', POSITION_RELATIVE . 'src');
defined('VENDOR_PATH') or define('VENDOR_PATH', POSITION_RELATIVE . 'vendor');
defined('EMAIL_PATH') or define('EMAIL_PATH', CACHE_PATH . '/email');
defined('DOCUMENT_PATH') or define('DOCUMENT_PATH', DATA_PATH . '/document');
defined('ILLUSTRATION_PATH') or define('ILLUSTRATION_PATH', DATA_PATH . '/illustration');
defined('DEFAULT_THEME_PATH') or define('DEFAULT_THEME_PATH', POSITION_RELATIVE . 'assets');
defined('CURRENT_THEME_PATH') or define('CURRENT_THEME_PATH', POSITION_RELATIVE . 'assets');
defined('FEED_PATH') or define('FEED_PATH', SITE_PATH . 'feed.xml');

defined('UploadDir') or define('UploadDir', DATA_PATH . '/upload/');
defined('ParameterFilePath') or define('ParameterFilePath', CACHE_PATH . '/config-user.php');
defined('BanFilePath') or define('BanFilePath', CACHE_PATH . '/ban.json');
defined('GaleryFilePath') or define('GaleryFilePath', CACHE_PATH . '/galery.json');

// Others
defined('DEBUG') or define('DEBUG', true); // true, false
defined('SecurityKey') or define('SecurityKey', '$2a$12$SH2SVrFpLOsneL5lHZRmuuSffz7yisadguqJfDgh1yiGUhBB91pV.'); // bcrypt
defined('LastModification') or define('LastModification', mktime(0, 0, 0, 02, 04, 2016));
defined('LastModificationActualites') or define('LastModificationActualites', LastModification);
defined('HAAT') or define('HAAT', 'haat');
defined('DOHOT') or define('DOHOT', 'dohot');
defined('NbParPage') or define('NbParPage', '2');
defined('NbParPageAdmin') or define('NbParPageAdmin', '20');
defined('NbMaxLienPagination') or define('NbMaxLienPagination', '10');
defined('NbItemPerFeed') or define('NbItemPerFeed', '20');
defined('EmailAdmin') or define('EmailAdmin', 'olivier@maridat.com');
if (DEBUG) {
    defined('EmailFlambeaux') or define('EmailFlambeaux', EmailAdmin);
    defined('EmailGDJ') or define('EmailGDJ', EmailAdmin);
    defined('EmailContact') or define('EmailContact', EmailAdmin);
}
else {
    defined('EmailFlambeaux') or define('EmailFlambeaux', 'flambeaux@cepsaintmaur.fr');
    defined('EmailGDJ') or define('EmailGDJ', 'gdj@cepsaintmaur.fr');
    defined('EmailContact') or define('EmailContact', 'contact@cepsaintmaur.fr');
}
defined('CodeStats') or define('CodeStats', '');
defined('CodeWebmasterTools') or define('CodeWebmasterTools', '');
defined('Antibot') or define('Antibot', 12);
defined('DisplayHelp') or define('DisplayHelp', 1);
defined('SessionTimeoutKeepConnected') or define('SessionTimeoutKeepConnected', 60 * 60 * 24 * 365); // 1 year
defined('SessionTimeoutNormal') or define('SessionTimeoutNormal', 60 * 60 * 2); // 2 hours
defined('LoginTryNumber') or define('LoginTryNumber', 5); // 2 hours
defined('LoginBanishedTimeout') or define('LoginBanishedTimeout', 30 * 60); // 30 min
defined('CacheEnabled') or define('CacheEnabled', ! DEBUG);
defined('CompressionEnabled') or define('CompressionEnabled', false);
defined('StyleEnabled') or define('StyleEnabled', true);

define('OK_REDIRECTION', 1);
define('OK_NONBLOQUANT', 2);
define('OK_BLOQUANT', 3);
define('ERREUR_BLOQUANT', 0);
define('ERREUR_NONBLOQUANT', - 1);
define('ERREUR_REDIRECTION', - 2);
define('ERREUR', 0);
define('OK', 1);
define('NEUTRE', 2);

// Config
if (DEBUG) {
    ini_set('display_errors', true);
    ini_set('log_errors', true);
}
error_reporting(E_ALL);
ini_set('error_log', CACHE_PATH . '/error_log.txt');
define('MessageLog', CACHE_PATH . '/message_log.txt');
date_default_timezone_set('Europe/Paris');
ini_set('session.use_cookies', 1); // Use cookies to store session.
ini_set('session.use_only_cookies', 1); // Force cookies for session (phpsessionID forbidden in URL)
ini_set('session.use_trans_sid', false); // Prevent php to use sessionID in URL if cookies are disabled.
ini_set('session.save_path', 'sessions'); // Prevent php to use sessionID in URL if cookies are disabled.
if (! is_dir('sessions'))
    mkdir('sessions');
session_name('cepsaintmaur');
if (session_id() == '')
    session_start();
if (CompressionEnabled)
    ob_start('ob_gzhandler');