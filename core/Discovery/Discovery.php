<?php

namespace Core\Discovery;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

final class Discovery
{
    /**
     * @var array<string, string>
     */
    private array $directories;

    /**
     * @param array<string, string> $directories
     */
    public function __construct(array $directories = [])
    {
        $this->directories = $directories;
    }

    /**
     * @param array<string, array<int, string>> $directories
     * @return array<string, array<int, string>> paths to module.json
     */
    public function scan(array $directories = []): array
    {
        if ($directories !== []) {
            $this->directories = $directories;
        }

        $results = [];

        foreach ($this->directories as $type => $path) {
            if (!is_dir($path)) {
                $results[$type] = [];

                continue;
            }

            $entries = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS)
            );

            foreach ($entries as $entry) {
                if (strcasecmp($entry->getFilename(), 'module.json') !== 0) {
                    continue;
                }

                $results[$type][] = $entry->getPathname();
            }
        }

        return $results;
    }
}
