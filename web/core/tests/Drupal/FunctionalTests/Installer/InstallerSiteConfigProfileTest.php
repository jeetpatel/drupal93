<?php

namespace Drupal\FunctionalTests\Installer;

/**
 * Verifies that the installer defaults to the existing site email address and
 * timezone, if they were provided by the install profile.
 *
 * @group Installer
 */
class InstallerSiteConfigProfileTest extends InstallerTestBase
{
  /**
   * {@inheritdoc}
   */
    protected $defaultTheme = 'stark';

    /**
     * {@inheritdoc}
     */
    protected $profile = 'testing_site_config';

    /**
     * The site mail we expect to be set from the install profile.
     *
     * @see testing_site_config_install()
     */
    public const EXPECTED_SITE_MAIL = 'profile-testing-site-config@example.com';

    /**
     * The timezone we expect to be set from the install profile.
     *
     * @see testing_site_config_install()
     */
    public const EXPECTED_TIMEZONE = 'America/Los_Angeles';

    /**
     * {@inheritdoc}
     */
    protected function installParameters()
    {
        $parameters = parent::installParameters();

        // Don't override the site email address, allowing it to default to the one
        // from our install profile.
        unset($parameters['forms']['install_configure_form']['site_mail']);

        return $parameters;
    }

    /**
     * {@inheritdoc}
     */
    protected function setUpSite()
    {
        $this->assertSession()->fieldValueEquals('site_mail', self::EXPECTED_SITE_MAIL);
        $this->assertSession()->fieldValueEquals('date_default_timezone', self::EXPECTED_TIMEZONE);

        return parent::setUpSite();
    }

    /**
     * Verify the correct site config was set.
     */
    public function testInstaller()
    {
        $this->assertEquals(self::EXPECTED_SITE_MAIL, $this->config('system.site')->get('mail'));
        $this->assertEquals(self::EXPECTED_TIMEZONE, $this->config('system.date')->get('timezone.default'));
    }
}
