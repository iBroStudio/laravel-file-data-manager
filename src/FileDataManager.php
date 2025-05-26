<?php

namespace IBroStudio\FileDataManager;

use IBroStudio\FileDataManager\Contracts\FileManagerContract;
use Illuminate\Filesystem\Filesystem;

final class FileDataManager
{
    private string $content;

    private mixed $value;

    private function __construct(
        private string $file,
        private Filesystem $filesystem,
        private FileManagerContract $adapter,
    ) {
        $this->content = $this->filesystem->get($this->file);
        $this->value = null;
    }

    public static function load(string $file): static
    {
        $filesystem = app(Filesystem::class);
        $fileTypes = app(FileTypes::class);
        $driver = $filesystem->extension($file);

        return new self(
            file: $file,
            filesystem: $filesystem,
            adapter: $fileTypes->driver($driver),
        );
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function findValue(string $key): self
    {
        $this->value = $this->adapter->findValue($this->content, $key);

        return $this;
    }

    public function findArray(string $key): self
    {
        $this->value = $this->adapter->findArray($this->content, $key);

        return $this;
    }

    public function findRegex(string $regex): self
    {
        $this->value = $this->adapter->findRegex($this->content, $regex);

        return $this;
    }

    public function replaceValue(string $key, mixed $value): self
    {
        $this->content = $this->adapter->replaceValue($this->content, $key, $value);

        return $this;
    }

    public function addArrayValue(string $key, mixed $value): self
    {
        $this->content = $this->adapter->addArrayValue($this->content, $key, $value);

        return $this;
    }

    public function addRegexValue(string $regex, string $value): self
    {
        $this->content = $this->adapter->addRegexValue($this->content, $regex, $value);

        return $this;
    }

    public function write(): void
    {
        $this->filesystem->replace($this->file, $this->content);
    }
}
