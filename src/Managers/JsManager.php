<?php

namespace IBroStudio\FileDataManager\Managers;

use IBroStudio\FileDataManager\Contracts\FileManagerContract;
use Illuminate\Support\Str;

class JsManager implements FileManagerContract
{
    public function findValue(string $content, string $key): string|array
    {
        $key = Str::of($key)
            ->replace('.', '\.');

        //dd(Str::of(Str::match('#' . $key->value() . '\:\s?(.*?)\,#s', $content))->value()); // important: '.{{ plugin_name }}',
        //dd(Str::of(Str::match('#' . $key->value() . '\s?=\s?{(.*)}#s', $content))->value());
        return Str::of(Str::match('#' . $key->value() . '\:\s?(.*?)\,#s', $content))
            ->trim('\'')
            ->trim('"');
    }

    public function findArray(string $content, string $key): array
    {
        $array = Str::of(Str::match('#' . $key . '\:\s?\[(.*?)\]#s', $content))
            ->squish();
        $array = rtrim($array, ',');
        $array = str_replace(' ', '', rtrim($array, ','));

        return strlen($array) ? explode(',', $array) : [];
    }

    public function findRegex(string $content, string $regex): array
    {
        return Str::matchAll(Str::of($regex), $content)->toArray();
    }

    public function replaceValue(string $content, string $key, mixed $value): string
    {
        return Str::replace(
            $this->findValue($content, $key),
            $value,
            $content
        );
    }

    public function addArrayValue(string $content, string $key, mixed $value): string
    {
        return Str::replace(
            Str::match('#(' . $key . '\:\s?\[(.*?)\])#s', $content),
            "$key: [\n" . implode(",\n", array_merge($this->findArray($content, $key), [$value])) . "\n]",
            $content
        );
    }

    public function addRegexValue(string $content, string $regex, string $value): string
    {
        $current = Str::matchAll(Str::of($regex), $content)->toArray();
        $last_index = count($current) - 1;
        $last_item = $current[$last_index];
        $current[$last_index] .= "\n" . $value;

        return Str::replace(
            $last_item,
            $current[$last_index],
            $content
        );
    }
}
