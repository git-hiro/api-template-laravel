<?php

namespace App\Repositories\Datasources;

use Closure;
use Illuminate\Database\DatabaseManager;
use Throwable;

class MultipleConnection
{
  protected $db;

  public function __construct(DatabaseManager $db)
  {
    $this->db = $db;
  }

  public function getConnection(string $name)
  {
    return $this->db->connection($name);
  }

  public function beginTransaction(array $names)
  {
    foreach ($names as $name) {
      $this->getConnection($name)->beginTransaction();
    }
  }

  public function commit(array $names)
  {
    foreach ($names as $name) {
      $this->getConnection($name)->commit();
    }
  }

  public function rollBack(array $names)
  {
    foreach ($names as $name) {
      $this->getConnection($name)->rollBack();
    }
  }

  public function transaction(array $names, Closure $callback)
  {
    $this->beginTransaction($names);

    try {
      return tap($callback($this), function () use ($names) {
        $this->commit($names);
      });
    } catch (Throwable $th) {
      $this->rollBack($names);

      throw $th;
    }
  }
}
