<?php

namespace core;

use core\Singleton;
use core\FileSystem\FileSystemInterface;
use core\DependencyInjection\Container;

class Db
{
    use Singleton;

    protected \PDO $pdo;
    public static int $amountQueries = 0;
    protected FileSystemInterface $fileSystem;

    /**
     * @var array<string>
     */
    public static array $queries = [];

    protected function __construct()
    {
        $this->fileSystem = Container::getInstance()->get(FileSystemInterface::class);

        $db = $this->fileSystem->getRequire(ROOT . '/config/configDb.php');
        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ];

        $this->pdo = new \PDO($db['dsn'], $db['user'], $db['password'], $options);
    }

    /**
     * @param string $sql
     * @param array<int|string> $params
     * 
     * @return bool
     */
    public function execute(string $sql, array $params = []): bool
    {
        self::$amountQueries++;
        self::$queries[] = $sql;
        $statemant = $this->pdo->prepare($sql);
        return $statemant->execute($params);
    }

    /**
     * @param string $sql
     * @param array<int|string> $params
     * 
     * @return array<array<string,string|int|float>>
     */
    public function query(string $sql, array $params = []): array
    {
        self::$amountQueries++;
        self::$queries[] = $sql;
        $statemant = $this->pdo->prepare($sql);
        $result = $statemant->execute($params);
        
        if ($result !== false) {
            return $statemant->fetchAll();
        }

        return [];
    }

    public function beginTransaction(): void
    {
        $this->pdo->beginTransaction();
    }

    public function rollBack(): void
    {
        $this->pdo->rollBack();
    }

    public function commit(): void
    {
        $this->pdo->commit();
    }

    /**
     * @return array<string>
     */
    public function getQueries(): array
    {
        return self::$queries;
    }

    public function getAmountQueries(): int
    {
        return self::$amountQueries;
    }
}
