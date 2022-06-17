<?php

namespace IBroStudio\FileDataManager\Managers;

use IBroStudio\FileDataManager\Contracts\FileManagerContract;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class JsonManager implements FileManagerContract
{
    public function parse($content): array
    {
        return json_decode(trim($content), true);
    }

    public function render($content): string
    {
        return json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function findValue(string $content, string $key): string|array
    {
        return Arr::get($this->parse($content), $key);
    }

    public function findArray(string $content, string $key): array
    {
        return (array) $this->findValue($content, $key);
    }

    public function findRegex(string $content, string $regex): array
    {
        return Str::matchAll(Str::of($regex), $content)->toArray();
    }

    public function replaceValue(string $content, string $key, mixed $value): string
    {
        $current = $this->parse($content);
        Arr::forget($current, $key);

        return $this->render(Arr::add($current, $key, $value));
    }

    public function addArrayValue(string $content, string $key, mixed $value): string
    {
        $current = $this->parse($content);
        $current_property = Arr::get($current, $key);
        Arr::forget($current, $key);

        return $this->render(Arr::add($current, $key, array_merge($current_property, [$value])));
    }

    public function addValue($content, $key, $value): string
    {
        $current = $this->parse($content);
        $current_property = Arr::get($current, $key);
        $new_property = is_array($current_property) ? array_merge($current_property, [$value]) : $value;
        Arr::forget($current, $key);

        return $this->render(Arr::add($current, $key, $new_property));
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
