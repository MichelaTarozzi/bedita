<?php
/**
 ${BEDITA_LICENSE_HEADER}
 */
namespace BEdita\Core\Utils;

use Cake\Database\Connection;
use Cake\Datasource\ConnectionManager;

/**
 * Database utilities class
 *
 * Provides static methods to common db related operations
 */
class DbUtils
{

    /**
     * Returns an array with current database schema information (tables, columns,
     * indexes, constraints)
     * Using $dbConfig database connection ('default' as default)
     *
     * @param string $dbConfig Input database configuration ('default' as default)
     *
     * @return array containing complete schema information, table names as keys
     *     and details on columns, indexes and constraints for every table
     */
    public static function currentSchema($dbConfig = 'default')
    {
        $schema = [];
        $connection = ConnectionManager::get($dbConfig);
        if (!($connection instanceof Connection)) {
            return $schema;
        }

        $collection = $connection->schemaCollection();
        $tables = $collection->listTables();
        foreach ($tables as $tableName) {
            $schema[$tableName] = [];
            $table = $collection->describe($tableName);
            $columns = $table->columns();
            foreach ($columns as $col) {
                $schema[$tableName]['columns'][$col] = $table->column($col);
            }
            $constraints = $table->constraints();
            foreach ($constraints as $cons) {
                $schema[$tableName]['constraints'][$cons] = $table->constraint($cons);
            }
            $indexes = $table->indexes();
            foreach ($indexes as $idx) {
                $schema[$tableName]['indexes'][$idx] = $table->index($idx);
            }
        }
        return $schema;
    }

    /**
     * Compare schema arrays betweend $expected and $current schema metadata
     * Returns an array with difference details
     *
     * @param array $expected Expected db schema
     * @param array $current Current db schema from DbUtils::currentSchema()
     *
     * @return array containing information on differences found
     */
    public static function schemaCompare(array $expected, array $current)
    {
        $diff = [];
        foreach ($expected as $table => $tableMeta) {
            if (empty($current[$table])) {
                $diff['missing']['tables'][] = $table;
                continue;
            }
            $itemNames = ['columns', 'constraints', 'indexes'];
            foreach ($itemNames as $itemName) {
                if (empty($tableMeta[$itemName])) {
                    continue;
                }
                if (!isset($current[$table][$itemName])) {
                    $current[$table][$itemName] = [];
                }
                static::compareSchemaItems(
                    $table,
                    $itemName,
                    $tableMeta[$itemName],
                    $current[$table][$itemName],
                    $diff
                );
            }
        }
        return $diff;
    }

    /**
     * Compare schema related arrays relative to some $itemType ('columns', 'constraints', 'indexes')
     * Populate $diff array with differences on 3 keys:
     *  - 'missing' items expected but not found
     *  - 'changed' items with different metadata
     *  - 'exceeding' items not present in expected data
     *
     * @param string $table Current table
     * @param string $itemType Item type ('columns', 'constraints', 'indexes'()
     * @param array $expItems Expected items data
     * @param array $currItems Current items data
     * @param array $diff Difference array
     *
     * @return void
     */
    protected static function compareSchemaItems($table, $itemType, array $expItems, array $currItems, array &$diff)
    {
        foreach ($expItems as $key => $data) {
            if (empty($currItems[$key])) {
                $diff['missing'][$itemType][] = $table . '.' . $key;
                continue;
            }

            if ($currItems[$key] != $data) {
                $diff['changed'][$itemType][] = $table . '.' . $key;
            }
        }
        $exceeding = array_diff_key($currItems, $expItems);
        foreach ($exceeding as $key => $data) {
            $diff['exceeding'][$itemType][] = $table . '.' . $key;
        }
    }

    /**
     * Get basic database connection info
     *
     * @param string $dbConfig input database configuration ('default' as default)
     *
     * @return array containing requested configuration
     *          + 'vendor' key (mysql, sqlite, postgres,...)
     */
    public static function basicInfo($dbConfig = 'default')
    {
        $config = ConnectionManager::get($dbConfig)->config();
        $config['vendor'] = strtolower(substr($config['driver'], strrpos($config['driver'], '\\') + 1));
        return $config;
    }

    /**
     * Executes SQL query using transactions.
     * Returns an array providing information on SQL query results
     *
     * @param string $sql      SQL query to execute.
     * @param string $dbConfig Database config to use ('default' as default)
     *
     * @return array containing keys: 'success' (boolean), 'error' (string with error message),
     *      'rowCount' (number of affected rows)
     * @throws \Cake\Datasource\Exception\MissingDatasourceConfigException Throws an exception
     *      if the requested `$dbConfig` does not exist.
     */
    public static function executeTransaction($sql, $dbConfig = 'default')
    {
        $res = ['success' => false, 'error' => '', 'rowCount' => 0];
        $connection = ConnectionManager::get($dbConfig);
        try {
            $connection->begin();
            $statement = $connection->query($sql);
            $connection->commit();
            $res['success'] = true;
            $res['rowCount'] = $statement->rowCount();
        } catch (\Exception $e) {
            $connection->rollback();
            $res['error'] = $e->getMessage();
        }
        return $res;
    }
}