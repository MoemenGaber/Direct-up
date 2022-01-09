<?php

/**
 * Configs(htaccess, wp-config ...) params descriptions
 *
 * @category  Duplicator
 * @package   Installer
 * @author    Snapcreek <admin@snapcreek.com>
 * @copyright 2011-2021  Snapcreek LLC
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 */

namespace Duplicator\Installer\Core\Params\Descriptors;

use Duplicator\Installer\Core\Params\PrmMng;
use Duplicator\Installer\Addons\ProBase\License;
use Duplicator\Installer\Core\Params\Items\ParamItem;
use Duplicator\Installer\Core\Params\Items\ParamForm;
use Duplicator\Installer\Core\Params\Items\ParamOption;
use Duplicator\Installer\Utils\Log\Log;
use DUPX_InstallerState;
use DUPX_Template;
use DUPX_TemplateItem;
use Exception;

/**
 * class where all parameters are initialized. Used by the param manager
 */
final class ParamDescConfigs implements DescriptorInterface
{

    /**
     * Init params
     *
     * @param ParamItem[]|ParamForm[] $params params list
     *
     * @return void
     */
    public static function init(&$params)
    {
        $params[PrmMng::PARAM_INST_TYPE] = new ParamForm(
            PrmMng::PARAM_INST_TYPE,
            ParamForm::TYPE_INT,
            ParamForm::FORM_TYPE_RADIO,
            array(
                'default'        => DUPX_InstallerState::INSTALL_NOT_SET,
                'acceptValues'   => array(__CLASS__, 'getInstallTypesAcceptValues'),
                'invalidMessage' => 'Multisite install type invalid value'
            ),
            array(
                'status' => ParamForm::STATUS_ENABLED,
                'label'          => 'Install Type:',
                'wrapperClasses' => array('group-block', 'revalidate-on-change'),
                'options'        => self::getInstallTypeOptions()
            )
        );

        $params[PrmMng::PARAM_WP_CONFIG] = new ParamForm(
            PrmMng::PARAM_WP_CONFIG,
            ParamForm::TYPE_STRING,
            ParamForm::FORM_TYPE_SELECT,
            array(
                'default'      => 'modify',
                'acceptValues' => array(
                    'modify',
                    'nothing',
                    'new'
                )
            ),
            array(
                'label'          => 'WordPress wp-config:',
                'wrapperClasses' => 'medium',
                'status'         => function (ParamItem $paramObj) {
                    if (
                        DUPX_InstallerState::isRestoreBackup() ||
                        DUPX_InstallerState::isAddSiteOnMultisite()
                    ) {
                        return ParamForm::STATUS_INFO_ONLY;
                    } else {
                        return ParamForm::STATUS_ENABLED;
                    }
                },
                'options' => array(
                    new ParamOption('nothing', 'Do nothing'),
                    new ParamOption('modify', 'Modify original'),
                    new ParamOption('new', 'Create new from wp-config sample')
                )
            )
        );

        $params[PrmMng::PARAM_HTACCESS_CONFIG] = new ParamForm(
            PrmMng::PARAM_HTACCESS_CONFIG,
            ParamForm::TYPE_STRING,
            ParamForm::FORM_TYPE_SELECT,
            array(
                'default'      => 'new',
                'acceptValues' => array(
                    'new',
                    'original',
                    'nothing'
                )
            ),
            array(
                'label'          => 'Apache .htaccess:',
                'wrapperClasses' => 'medium',
                'status'         => function (ParamItem $paramObj) {
                    if (
                        DUPX_InstallerState::isRestoreBackup() ||
                        DUPX_InstallerState::isAddSiteOnMultisite()
                    ) {
                        return ParamForm::STATUS_INFO_ONLY;
                    } else {
                        return ParamForm::STATUS_ENABLED;
                    }
                },
                'options' => array(
                    new ParamOption('nothing', 'Do nothing'),
                    new ParamOption('original', 'Retain original'),
                    new ParamOption('new', 'Create new')
                )
            )
        );

        $params[PrmMng::PARAM_OTHER_CONFIG] = new ParamForm(
            PrmMng::PARAM_OTHER_CONFIG,
            ParamForm::TYPE_STRING,
            ParamForm::FORM_TYPE_SELECT,
            array(
                'default'      => 'new',
                'acceptValues' => array(
                    'new',
                    'original',
                    'nothing'
                )
            ),
            array(
                'label'          => 'Other Configurations (web.config, user.ini):',
                'wrapperClasses' => 'medium',
                'status'         => function (ParamItem $paramObj) {
                    if (
                        DUPX_InstallerState::isRestoreBackup() ||
                        DUPX_InstallerState::isAddSiteOnMultisite()
                    ) {
                        return ParamForm::STATUS_INFO_ONLY;
                    } else {
                        return ParamForm::STATUS_ENABLED;
                    }
                },
                'options' => array(
                    new ParamOption('nothing', 'Do nothing'),
                    new ParamOption('original', 'Retain original'),
                    new ParamOption('new', 'Reset')
                )
            )
        );
    }

    /**
     * Update params after overwrite logic
     *
     * @param ParamItem[]|ParamForm[] $params params list
     *
     * @return void
     */
    public static function updateParamsAfterOverwrite($params)
    {
        $installType = $params[PrmMng::PARAM_INST_TYPE]->getValue();
        if ($installType == DUPX_InstallerState::INSTALL_NOT_SET) {
            $acceptValues = $params[PrmMng::PARAM_INST_TYPE]->getAcceptValues();
            $params[PrmMng::PARAM_INST_TYPE]->setValue(self::getInstTypeByPriority($acceptValues));
        }

        $installType = $params[PrmMng::PARAM_INST_TYPE]->getValue();
        if (DUPX_InstallerState::isRestoreBackup($installType)) {
            if (\DUPX_Custom_Host_Manager::getInstance()->isManaged()) {
                $params[PrmMng::PARAM_WP_CONFIG]->setValue('nothing');
                $params[PrmMng::PARAM_HTACCESS_CONFIG]->setValue('nothing');
                $params[PrmMng::PARAM_OTHER_CONFIG]->setValue('nothing');
            } else {
                $params[PrmMng::PARAM_WP_CONFIG]->setValue('modify');
                $params[PrmMng::PARAM_HTACCESS_CONFIG]->setValue('original');
                $params[PrmMng::PARAM_OTHER_CONFIG]->setValue('original');
            }
        }
    }

    /**
     * Return default install type from install types enabled
     *
     * @param  int[] $acceptValues install types enabled
     *
     * @return int
     */
    protected static function getInstTypeByPriority($acceptValues)
    {
        $defaultPriority = array(
            DUPX_InstallerState::INSTALL_RECOVERY_MULTISITE_SUBDOMAIN,
            DUPX_InstallerState::INSTALL_RECOVERY_MULTISITE_SUBFOLDER,
            DUPX_InstallerState::INSTALL_RECOVERY_SINGLE_SITE,
            DUPX_InstallerState::INSTALL_RBACKUP_MULTISITE_SUBDOMAIN,
            DUPX_InstallerState::INSTALL_RBACKUP_MULTISITE_SUBFOLDER,
            DUPX_InstallerState::INSTALL_RBACKUP_SINGLE_SITE,
            DUPX_InstallerState::INSTALL_SINGLE_SITE_ON_SUBDOMAIN,
            DUPX_InstallerState::INSTALL_SINGLE_SITE_ON_SUBFOLDER,
            DUPX_InstallerState::INSTALL_SUBSITE_ON_SUBDOMAIN,
            DUPX_InstallerState::INSTALL_SUBSITE_ON_SUBFOLDER,
            DUPX_InstallerState::INSTALL_MULTISITE_SUBDOMAIN,
            DUPX_InstallerState::INSTALL_MULTISITE_SUBFOLDER,
            DUPX_InstallerState::INSTALL_SINGLE_SITE,
            DUPX_InstallerState::INSTALL_STANDALONE
        );

        foreach ($defaultPriority as $current) {
            if (in_array($current, $acceptValues)) {
                return $current;
            }
        }

        throw new Exception('No default value found on proprity list');
    }

    /**
     *
     * @return ParamOption[]
     */
    protected static function getInstallTypeOptions()
    {
        $result = array();

        $option   = new ParamOption(DUPX_InstallerState::INSTALL_RBACKUP_SINGLE_SITE, 'Restore single site', array(__CLASS__, 'typeOptionsVisibility'));
        $option->setNote(array(__CLASS__, 'getInstallTypesNotes'));
        $result[] = $option;

        $option   = new ParamOption(
            DUPX_InstallerState::INSTALL_RBACKUP_MULTISITE_SUBDOMAIN,
            'Restore multisite network',
            array(__CLASS__, 'typeOptionsVisibility')
        );
        $option->setNote(array(__CLASS__, 'getInstallTypesNotes'));
        $result[] = $option;

        $option   = new ParamOption(
            DUPX_InstallerState::INSTALL_RBACKUP_MULTISITE_SUBFOLDER,
            'Restore multisite network',
            array(__CLASS__, 'typeOptionsVisibility')
        );
        $option->setNote(array(__CLASS__, 'getInstallTypesNotes'));
        $result[] = $option;

        $option   = new ParamOption(
            DUPX_InstallerState::INSTALL_SINGLE_SITE,
            'Install single site',
            array(__CLASS__, 'typeOptionsVisibility')
        );
        $option->setNote(array(__CLASS__, 'getInstallTypesNotes'));
        $result[] = $option;

        $option   = new ParamOption(
            DUPX_InstallerState::INSTALL_MULTISITE_SUBDOMAIN,
            'Install multisite network',
            array(__CLASS__, 'typeOptionsVisibility')
        );
        $option->setNote(array(__CLASS__, 'getInstallTypesNotes'));
        $result[] = $option;

        $option   = new ParamOption(
            DUPX_InstallerState::INSTALL_MULTISITE_SUBFOLDER,
            'Install multisite network',
            array(__CLASS__, 'typeOptionsVisibility')
        );
        $option->setNote(array(__CLASS__, 'getInstallTypesNotes'));
        $result[] = $option;

        $option   = new ParamOption(
            DUPX_InstallerState::INSTALL_STANDALONE,
            'Convert subsite to standalone',
            array(__CLASS__, 'typeOptionsVisibility')
        );
        $option->setNote(array(__CLASS__, 'getInstallTypesNotes'));
        $result[] = $option;

        $option   = new ParamOption(
            DUPX_InstallerState::INSTALL_SINGLE_SITE_ON_SUBDOMAIN,
            'Install single site into multisite network',
            array(__CLASS__, 'typeOptionsVisibility')
        );
        $option->setNote(array(__CLASS__, 'getInstallTypesNotes'));
        $result[] = $option;

        $option   = new ParamOption(
            DUPX_InstallerState::INSTALL_SINGLE_SITE_ON_SUBFOLDER,
            'Install single site into multisite network',
            array(__CLASS__, 'typeOptionsVisibility')
        );
        $option->setNote(array(__CLASS__, 'getInstallTypesNotes'));
        $result[] = $option;

        $option   = new ParamOption(
            DUPX_InstallerState::INSTALL_SUBSITE_ON_SUBDOMAIN,
            'Install subsite into multisite network',
            array(__CLASS__, 'typeOptionsVisibility')
        );
        $option->setNote(array(__CLASS__, 'getInstallTypesNotes'));
        $result[] = $option;

        $option   = new ParamOption(
            DUPX_InstallerState::INSTALL_SUBSITE_ON_SUBFOLDER,
            'Install subsite into multisite network',
            array(__CLASS__, 'typeOptionsVisibility')
        );
        $option->setNote(array(__CLASS__, 'getInstallTypesNotes'));
        $result[] = $option;

        $option   = new ParamOption(
            DUPX_InstallerState::INSTALL_RECOVERY_SINGLE_SITE,
            'Recovery single site',
            array(__CLASS__, 'typeOptionsVisibility')
        );
        $option->setNote(array(__CLASS__, 'getInstallTypesNotes'));
        $result[] = $option;

        $option   = new ParamOption(
            DUPX_InstallerState::INSTALL_RECOVERY_MULTISITE_SUBDOMAIN,
            'Recovery multisite network',
            array(__CLASS__, 'typeOptionsVisibility')
        );
        $option->setNote(array(__CLASS__, 'getInstallTypesNotes'));
        $result[] = $option;

        $option   = new ParamOption(
            DUPX_InstallerState::INSTALL_RECOVERY_MULTISITE_SUBFOLDER,
            'Recovery multisite network',
            array(__CLASS__, 'typeOptionsVisibility')
        );
        $option->setNote(array(__CLASS__, 'getInstallTypesNotes'));
        $result[] = $option;

        return $result;
    }

    /**
     * Return option type status
     *
     * @param ParamOption $option install type option
     *
     * @return string option status
     */
    public static function typeOptionsVisibility(ParamOption $option)
    {
        $archiveConfig = \DUPX_ArchiveConfig::getInstance();
        $overwriteData = PrmMng::getInstance()->getValue(PrmMng::PARAM_OVERWRITE_SITE_DATA);
        $isOwrMode     = PrmMng::getInstance()->getValue(PrmMng::PARAM_INSTALLER_MODE) === DUPX_InstallerState::MODE_OVR_INSTALL;

        switch ($option->value) {
            case DUPX_InstallerState::INSTALL_SINGLE_SITE:
                if ($archiveConfig->mu_mode != 0) {
                    return ParamOption::OPT_HIDDEN;
                }
                break;
            case DUPX_InstallerState::INSTALL_MULTISITE_SUBDOMAIN:
                if ($archiveConfig->mu_mode != 1) {
                    return ParamOption::OPT_HIDDEN;
                }
                break;
            case DUPX_InstallerState::INSTALL_MULTISITE_SUBFOLDER:
                if ($archiveConfig->mu_mode != 2) {
                    return ParamOption::OPT_HIDDEN;
                }
                break;
            case DUPX_InstallerState::INSTALL_STANDALONE:
                if ($archiveConfig->mu_mode == 0) {
                    return ParamOption::OPT_HIDDEN;
                }
                break;
            case DUPX_InstallerState::INSTALL_SINGLE_SITE_ON_SUBDOMAIN:
                if (!$isOwrMode || $archiveConfig->mu_mode > 0 || !$overwriteData['isMultisite'] || !$overwriteData['subdomain']) {
                    return ParamOption::OPT_HIDDEN;
                }
                break;
            case DUPX_InstallerState::INSTALL_SINGLE_SITE_ON_SUBFOLDER:
                if (!$isOwrMode || $archiveConfig->mu_mode > 0 || !$overwriteData['isMultisite'] || $overwriteData['subdomain']) {
                    return ParamOption::OPT_HIDDEN;
                }
                break;
            case DUPX_InstallerState::INSTALL_SUBSITE_ON_SUBDOMAIN:
                if (!$isOwrMode || $archiveConfig->mu_mode == 0 || !$overwriteData['isMultisite'] || !$overwriteData['subdomain']) {
                    return ParamOption::OPT_HIDDEN;
                }
                break;
            case DUPX_InstallerState::INSTALL_SUBSITE_ON_SUBFOLDER:
                if (!$isOwrMode || $archiveConfig->mu_mode == 0 || !$overwriteData['isMultisite'] || $overwriteData['subdomain']) {
                    return ParamOption::OPT_HIDDEN;
                }
                break;
            case DUPX_InstallerState::INSTALL_RBACKUP_SINGLE_SITE:
                if ($archiveConfig->mu_mode != 0 || !DUPX_InstallerState::isInstallerCreatedInThisLocation()) {
                    return ParamOption::OPT_HIDDEN;
                }
                break;
            case DUPX_InstallerState::INSTALL_RBACKUP_MULTISITE_SUBDOMAIN:
                if ($archiveConfig->mu_mode != 1 || !DUPX_InstallerState::isInstallerCreatedInThisLocation()) {
                    return ParamOption::OPT_HIDDEN;
                }
                break;
            case DUPX_InstallerState::INSTALL_RBACKUP_MULTISITE_SUBFOLDER:
                if ($archiveConfig->mu_mode != 2 || !DUPX_InstallerState::isInstallerCreatedInThisLocation()) {
                    return ParamOption::OPT_HIDDEN;
                }
                break;
            case DUPX_InstallerState::INSTALL_RECOVERY_SINGLE_SITE:
            case DUPX_InstallerState::INSTALL_RECOVERY_MULTISITE_SUBDOMAIN:
            case DUPX_InstallerState::INSTALL_RECOVERY_MULTISITE_SUBFOLDER:
                return ParamOption::OPT_HIDDEN;
            case DUPX_InstallerState::INSTALL_NOT_SET:
            default:
                throw new Exception('Install type not valid ' . $option->value);
        }

        $acceptValues = self::getInstallTypesAcceptValues();
        return in_array($option->value, $acceptValues) ? ParamOption::OPT_ENABLED : ParamOption::OPT_DISABLED;
    }

    /**
     *
     * @return int[]
     */
    public static function getInstallTypesAcceptValues()
    {
        $acceptValues  = array();
        $archiveConfig = \DUPX_ArchiveConfig::getInstance();

        if (PrmMng::getInstance()->getValue(PrmMng::PARAM_TEMPLATE) === DUPX_Template::TEMPLATE_RECOVERY) {
            switch ($archiveConfig->mu_mode) {
                case 0:
                    $acceptValues[] = DUPX_InstallerState::INSTALL_RECOVERY_SINGLE_SITE;
                    break;
                case 1:
                    $acceptValues[] = DUPX_InstallerState::INSTALL_RECOVERY_MULTISITE_SUBDOMAIN;
                    break;
                case 2:
                    $acceptValues[] = DUPX_InstallerState::INSTALL_RECOVERY_MULTISITE_SUBFOLDER;
                    break;
            }
            return $acceptValues;
        }

        $overwriteData = PrmMng::getInstance()->getValue(PrmMng::PARAM_OVERWRITE_SITE_DATA);
        $isManaged     = \DUPX_Custom_Host_Manager::getInstance()->isManaged();
        $isSameLocation = DUPX_InstallerState::isInstallerCreatedInThisLocation();

        switch ($archiveConfig->mu_mode) {
            case 0:
                $acceptValues[] = DUPX_InstallerState::INSTALL_SINGLE_SITE;
                if ($isSameLocation) {
                    $acceptValues[] = DUPX_InstallerState::INSTALL_RBACKUP_SINGLE_SITE;
                }
                break;
            case 1:
                if (!$isManaged && !$archiveConfig->isPartialNetwork()) {
                    $acceptValues[] = DUPX_InstallerState::INSTALL_MULTISITE_SUBDOMAIN;
                    if ($isSameLocation) {
                        $acceptValues[] = DUPX_InstallerState::INSTALL_RBACKUP_MULTISITE_SUBDOMAIN;
                    }
                }
                break;
            case 2:
                if (!$isManaged && !$archiveConfig->isPartialNetwork()) {
                    $acceptValues[] = DUPX_InstallerState::INSTALL_MULTISITE_SUBFOLDER;
                    if ($isSameLocation) {
                        $acceptValues[] = DUPX_InstallerState::INSTALL_RBACKUP_MULTISITE_SUBFOLDER;
                    }
                }
                break;
        }

        if (
            $archiveConfig->mu_mode > 0 &&
            License::multisitePlusEnabled()
        ) {
            $acceptValues[] = DUPX_InstallerState::INSTALL_STANDALONE;
        }

        if (
            DUPX_InstallerState::isImportFromBackendMode() &&
            $overwriteData['isMultisite'] &&
            License::multisitePlusEnabled()
        ) {
            if (version_compare($overwriteData['wpVersion'], DUPX_InstallerState::SUBSITE_IMPORT_WP_MIN_VERSION, '>=')) {
                if ($archiveConfig->mu_mode == 0) {
                    if ($overwriteData['subdomain']) {
                        $acceptValues[] = DUPX_InstallerState::INSTALL_SINGLE_SITE_ON_SUBDOMAIN;
                    } else {
                        $acceptValues[] = DUPX_InstallerState::INSTALL_SINGLE_SITE_ON_SUBFOLDER;
                    }
                } else {
                    if (DUPX_InstallerState::isMuImportEnabled()) {
                        if ($overwriteData['subdomain']) {
                            $acceptValues[] = DUPX_InstallerState::INSTALL_SUBSITE_ON_SUBDOMAIN;
                        } else {
                            $acceptValues[] = DUPX_InstallerState::INSTALL_SUBSITE_ON_SUBFOLDER;
                        }
                    }
                }
            } else {
                $msg = "The option to import the site into the multisite network has been disabled " .
                    "since it's only available for Wordpress <b>" . DUPX_InstallerState::SUBSITE_IMPORT_WP_MIN_VERSION . " +</b>.<br>";
                $msg .= " To overcome the issue please update Wordpress to the most recent version.";

                $noticeManager = \DUPX_NOTICE_MANAGER::getInstance();
                $noticeManager->addNextStepNotice(array(
                    'shortMsg'    => 'Import site into network disabled',
                    'level'       => \DUPX_NOTICE_ITEM::NOTICE,
                    'longMsg'     => $msg,
                    'longMsgMode' => \DUPX_NOTICE_ITEM::MSG_MODE_HTML
                ), \DUPX_NOTICE_MANAGER::ADD_UNIQUE, 'import-site-into-network-disabled');
            }
        }

        return $acceptValues;
    }

    /**
     * Return install type option note
     *
     * @param ParamOption $option install type option
     *
     * @return string
     */
    public static function getInstallTypesNotes(ParamOption $option)
    {
        switch ($option->value) {
            case DUPX_InstallerState::INSTALL_SINGLE_SITE:
            case DUPX_InstallerState::INSTALL_MULTISITE_SUBDOMAIN:
            case DUPX_InstallerState::INSTALL_MULTISITE_SUBFOLDER:
                return '';
            case DUPX_InstallerState::INSTALL_STANDALONE:
                return License::getLicenseNote(License::TYPE_BUSINESS_GOLD);
            case DUPX_InstallerState::INSTALL_SINGLE_SITE_ON_SUBDOMAIN:
            case DUPX_InstallerState::INSTALL_SINGLE_SITE_ON_SUBFOLDER:
            case DUPX_InstallerState::INSTALL_SUBSITE_ON_SUBDOMAIN:
            case DUPX_InstallerState::INSTALL_SUBSITE_ON_SUBFOLDER:
                $notes = array();
                $overwriteData = PrmMng::getInstance()->getValue(PrmMng::PARAM_OVERWRITE_SITE_DATA);
                if (!DUPX_InstallerState::isImportFromBackendMode()) {
                    $notes[] = 'This functionality is active only in the Drag&Drop import.';
                } else {
                    if (
                        !isset($overwriteData['wpVersion']) ||
                        version_compare($overwriteData['wpVersion'], DUPX_InstallerState::SUBSITE_IMPORT_WP_MIN_VERSION, '<')
                    ) {
                        $notes[] = 'Wordpress ' . DUPX_InstallerState::SUBSITE_IMPORT_WP_MIN_VERSION .
                            '+ is required on current multisite to enabled this function.';
                    }

                    if (
                        empty($notes) &&
                        !DUPX_InstallerState::isMuImportEnabled() &&
                        ($option->value == DUPX_InstallerState::INSTALL_SUBSITE_ON_SUBDOMAIN ||
                        $option->value == DUPX_InstallerState::INSTALL_SUBSITE_ON_SUBFOLDER)
                    ) {
                        $notes[] = 'To enable this option see Settings > General > Beta Features > MU Subsite Import';
                    }
                }

                if (strlen($licenseNote = License::getLicenseNote(License::TYPE_BUSINESS_GOLD)) > 0) {
                    $notes[] = $licenseNote;
                }
                return implode('<br>', $notes);
            case DUPX_InstallerState::INSTALL_RBACKUP_SINGLE_SITE:
            case DUPX_InstallerState::INSTALL_RBACKUP_MULTISITE_SUBDOMAIN:
            case DUPX_InstallerState::INSTALL_RBACKUP_MULTISITE_SUBFOLDER:
            case DUPX_InstallerState::INSTALL_RECOVERY_SINGLE_SITE:
            case DUPX_InstallerState::INSTALL_RECOVERY_MULTISITE_SUBDOMAIN:
            case DUPX_InstallerState::INSTALL_RECOVERY_MULTISITE_SUBFOLDER:
                return '';
            case DUPX_InstallerState::INSTALL_NOT_SET:
            default:
                throw new Exception('Install type not valid ' . $option->value);
        }
    }
}
