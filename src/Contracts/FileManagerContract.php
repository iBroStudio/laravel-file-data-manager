<?php

namespace IBroStudio\FileDataManager\Contracts;

interface FileManagerContract
{
    public function findValue(string $content, string $key): string|array;

    public function findArray(string $content, string $key): array;

    public function findRegex(string $content, string $regex): array;

    public function replaceValue(string $content, string $key, mixed $value): string;

    public function addArrayValue(string $content, string $key, mixed $value): string;

    public function addRegexValue(string $content, string $regex, string $value): string;
}
