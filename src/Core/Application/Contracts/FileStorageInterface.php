<?php

namespace Core\Application\Contracts;

interface FileStorageInterface
{
    public function store(string $path, array $file): string;

    public function delete(string $path);
}
