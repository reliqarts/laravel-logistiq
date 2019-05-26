<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq\Utility\Exceptions;

use ReliqArts\Logistiq\Exception;

class TableNameNotFound extends Exception
{
    /**
     * @var null|string
     */
    private $key;

    /**
     * @param string $key
     *
     * @return self
     */
    public static function forKey(string $key): self
    {
        $message = sprintf('Table name for key: `%s` not found!', $key);

        $instance = new self($message);
        $instance->key = $key;

        return $instance;
    }

    /**
     * @return null|string
     */
    public function getKey(): ?string
    {
        return $this->key;
    }
}
