<?php
/**
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @copyright John Flatness, Center for History and New Media, 2013-2014
 * @package OaiPmhRepository
 */
 
define('OAI_PMH_BASE_URL',WEB_ROOT.'/oai-pmh-repository/request');
define('OAI_PMH_REPOSITORY_PLUGIN_DIRECTORY',dirname(__FILE__));
 
 /**
  * OaiPmhRepository plugin class
  *
  * @package OaiPmhRepository
  */
class OaiPmhRepositoryPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array(
        'install',
        'config_form',
        'config',
        'uninstall',
        'initialize',
    );
    
    protected $_filters = array(
        'admin_dashboard_panels'
    );
    
    protected $_options = array(
        'oaipmh_repository_name',
        'oaipmh_repository_namespace_id',
        'oaipmh_repository_expose_files' => 1,
        'oaipmh_repository_expose_empty_collections' => 1,
        'oaipmh_repository_expose_item_type' => 0,
    );
    
    /**
     * OaiPmhRepository install hook.
     */
    public function hookInstall()
    {
        $this->_options['oaipmh_repository_name'] = get_option('site_title');
        $this->_options['oaipmh_repository_namespace_id'] = $this->_getServerName();
        $this->_installOptions();

        $db = get_db();
        /* Table: Stores currently active resumptionTokens

           id: primary key (also the value of the token)
           verb: Verb of original request
           metadata_prefix: metadataPrefix of original request
           cursor: Position of cursor within result set
           from: Optional from argument of original request
           until: Optional until argument of original request
           set: Optional set argument of original request
           expiration: Datestamp after which token is expired
        */
        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `{$db->prefix}oai_pmh_repository_tokens` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `verb` ENUM('ListIdentifiers', 'ListRecords', 'ListSets') COLLATE utf8_unicode_ci NOT NULL,
    `metadata_prefix` TEXT COLLATE utf8_unicode_ci NOT NULL,
    `cursor` INT(10) UNSIGNED NOT NULL,
    `from` DATETIME DEFAULT NULL,
    `until` DATETIME DEFAULT NULL,
    `set` INT(10) UNSIGNED DEFAULT NULL,
    `expiration` DATETIME NOT NULL,
    PRIMARY KEY  (`id`),
    INDEX(`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
SQL;
        $db->query($sql);
    }

    public function hookUninstall()
    {
        // Remove potential leftover options moved to config file
        delete_option('oaipmh_repository_record_limit');
        delete_option('oaipmh_repository_expiration_time');

        $this->_uninstallOptions();

        $db = get_db();
        $sql = "DROP TABLE IF EXISTS `{$db->prefix}oai_pmh_repository_tokens`;";
        $db->query($sql);
    }

    public function hookConfig($args)
    {
        $post = $args['post'];
        foreach ($this->_options as $optionKey => $optionValue) {
            if (is_numeric($optionKey)) {
                $optionKey = $optionValue;
            }
            if (isset($post[$optionKey])) {
                set_option($optionKey, $post[$optionKey]);
            }
        }
    }

    public function hookConfigForm()
    {
        $repoName = get_option('oaipmh_repository_name');
        $namespaceID = get_option('oaipmh_repository_namespace_id');
        $exposeFiles = get_option('oaipmh_repository_expose_files');
        include('config_form.php');
    }

    public function hookInitialize()
    {
        add_translation_source(dirname(__FILE__) . '/languages');
    }

    public function filterAdminDashboardPanels($panels)
    {
        ob_start();
?>
<h2><?php echo __('OAI-PMH Repository'); ?></h2>
<p><?php echo __('Harvester can access metadata from this site') . ' '; ?>
<a href="<?php echo OAI_PMH_BASE_URL; ?>"><?php echo OAI_PMH_BASE_URL; ?></a></p>
<?php
        $panels[] = ob_get_clean();
        return $panels;
    }

    private function _getServerName()
    {
        $name = preg_replace('/[^a-z0-9\-\.]/i', '', $_SERVER['SERVER_NAME']);
        if ($name == 'localhost') {
            $name = 'default.must.change';
        }

        return $name;
    }
}
