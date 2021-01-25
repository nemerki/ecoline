<?php

namespace Initbiz\Newsletter\Classes;

use Cache;
use Cms\Classes\Theme;
use Cms\Classes\Page as CmsPage;

class Helpers
{
    /**
     * Get newsletter management page url with injected email and token
     * @param  string $email subscriber's email
     * @param  string $token substriber's token
     * @return string        url of the newsletter management page
     */
    public static function getNewsletterManagementUrl($email, $token)
    {
        $pageUrl = Cache::get('newsletterManagementUrl');
        $emailVariable = Cache::get('newsletterManagementEmailVariable');
        $tokenVariable = Cache::get('newsletterManagementTokenVariable');

        if (!$pageUrl || !$emailVariable || !$tokenVariable) {
            $page = self::getPageWithComponent('newsletterConfirm');
            $properties = self::getComponentPropertiesFromPage($page, 'newsletterConfirm');

            $pageUrl = $page->url;
            $emailVariable = preg_replace('/[^a-zA-Z:]|\s/', "", $properties['email']);
            $tokenVariable = preg_replace('/[^a-zA-Z:]|\s/', "", $properties['token']);

            Cache::put('newsletterManagementUrl', $pageUrl, 10);
            Cache::put('newsletterManagementEmailVariable', $emailVariable, 10);
            Cache::put('newsletterManagementTokenVariable', $tokenVariable, 10);
        }

        $managementPageUrl = $pageUrl;
        $managementPageUrl = preg_replace('/' . $emailVariable . '/', $email, $managementPageUrl);
        $managementPageUrl = preg_replace('/' . $tokenVariable . '/', $token, $managementPageUrl);

        return url('/') . $managementPageUrl;
    }


    /**
     * Find page with the specified component
     * @param  string $componentName component's name
     * @return CmsPage               page containg the component
     */
    public static function getPageWithComponent($componentName)
    {
        $theme = Theme::getActiveTheme();
        $pages = CmsPage::listInTheme($theme, true);
        foreach ($pages as $page) {
            if ($page->hasComponent($componentName)) {
                return $page;
            }
        }
    }

    public static function getComponentPropertiesFromPage($page, $componentName)
    {
        foreach ($page['settings']['components'] as $tmpComponentName => $componentProperties) {
            $exp_key = explode(' ', $tmpComponentName);
            if ($exp_key[0] === $componentName) {
                return $page['settings']['components'][$tmpComponentName];
            }
        }
    }


    /**
     * Generate a random string, using a cryptographically secure
     * pseudorandom number generator (random_int)
     * https://stackoverflow.com/a/31107425
     *
     * For PHP 7, random_int is a PHP core function
     * For PHP 5.x, depends on https://github.com/paragonie/random_compat
     *
     * @param int $length      How many characters do we want?
     * @param string $keyspace A string of all possible characters
     *                         to select from
     * @return string
     */
    public static function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces[] = $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }

    public static function generateToken()
    {
        return self::random_str(40);
    }
}
