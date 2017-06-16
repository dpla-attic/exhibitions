<?php
/**
 * @package OaiPmhRepository
 * @author John Flatness, Yu-Hsun Lin
 * @copyright Copyright 2009 John Flatness, Yu-Hsun Lin
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
 
/**
 * Model class for resumption token table.
 *
 * @package OaiPmhRepository
 * @subpackage Models
 */
class OaiPmhRepositoryTokenTable extends Omeka_Db_Table
{
    /**
     * Deletes the rows for expired tokens from the table.
     */
    public function purgeExpiredTokens()
    {
        $db = $this->getDb();
        $db->delete(
            $this->getTableName(),
            'expiration <= ' . $db->quote(OaiPmhRepository_Date::unixToDb(time()))
        );
    }
}
