<?php

namespace FreePBX\modules\Filestore\drivers\FTP;

use League\Flysystem\Config;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\UnableToSetVisibility;
use League\Flysystem\UnableToRetrieveMetadata;
use League\Flysystem\UnableToSetMetadata;
use phpseclib3\Net\SFTP;
use League\Flysystem\StorageAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\DirectoryAttributes;

class PhpseclibV3SftpAdapter implements FilesystemAdapter
{
    private $sftp;

    public function __construct(SFTP $sftp)
    {
        $this->sftp = $sftp;
    }

    public function fileExists(string $path): bool
    {
        $path = '/' . ltrim($path, '/');
        return $this->sftp->file_exists($path);
    }

    public function directoryExists(string $path): bool
    {
        $path = '/' . ltrim($path, '/');
        return $this->sftp->is_dir($path);
    }

    public function write(string $path, string $contents, Config $config): void
    {
        $path = '/' . ltrim($path, '/');
        if (!$this->sftp->put($path, $contents)) {
            throw new UnableToWriteFile("Unable to write file at path: $path");
        }
    }

    public function writeStream(string $path, $resource, Config $config): void
    {
        $path = '/' . ltrim($path, '/');
        if (!$this->sftp->put($path, stream_get_contents($resource))) {
            throw new UnableToWriteFile("Unable to write stream to path: $path");
        }
    }

    public function read(string $path): string
    {
        $path = '/' . ltrim($path, '/');
        $contents = $this->sftp->get($path);
        if ($contents === false) {
            throw new UnableToReadFile("Unable to read file at path: $path");
        }
        return $contents;
    }

    public function readStream(string $path)
    {
        $path = '/' . ltrim($path, '/');
        $contents = $this->sftp->get($path);
        if ($contents === false) {
            throw new UnableToReadFile("Unable to read file at path: $path");
        }
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $contents);
        rewind($stream);
        return $stream;
    }

    public function delete(string $path): void
    {
        $path = '/' . ltrim($path, '/');
        if (!$this->sftp->delete($path)) {
            throw new UnableToDeleteFile("Unable to delete file at path: $path");
        }
    }

    public function deleteDirectory(string $path): void
    {
        $path = '/' . ltrim($path, '/');
        if (!$this->sftp->rmdir($path)) {
            throw new UnableToDeleteDirectory("Unable to delete directory at path: $path");
        }
    }

    public function createDirectory(string $path, Config $config): void
    {
        $path = '/' . ltrim($path, '/');
        if (!$this->sftp->mkdir($path)) {
            throw new UnableToCreateDirectory("Unable to create directory at path: $path");
        }
    }

    public function move(string $source, string $destination, Config $config): void
    {
        $path = '/' . ltrim($path, '/');
        if (!$this->sftp->rename($source, $destination)) {
            throw new UnableToMoveFile("Unable to move file from $source to $destination");
        }
    }


    public function copy(string $source, string $destination, Config $config): void
    {
        $path = '/' . ltrim($path, '/');
        $contents = $this->read($source);
        $this->write($destination, $contents, $config);
    }

    public function setVisibility(string $path, string $visibility): void
    {
        $path = '/' . ltrim($path, '/');
        $permissions = $visibility === 'public' ? 0644 : 0600;
        if (!$this->sftp->chmod($permissions, $path)) {
            throw new UnableToSetVisibility("Unable to set visibility for file at path: $path");
        }
    }

    public function visibility(string $path): FileAttributes
    {
        $path = '/' . ltrim($path, '/');
        $stat = $this->sftp->stat($path);
        if ($stat === false) {
            throw new UnableToRetrieveMetadata("Unable to retrieve visibility for file at path: $path");
        }

        $permissions = $stat['mode'] & 0777;
        $visibility = ($permissions & 0044) ? 'public' : 'private';

        return new FileAttributes($path, null, $visibility);
    }

    public function mimeType(string $path): FileAttributes
    {
        $path = '/' . ltrim($path, '/');
        $mimeType = mime_content_type($this->sftp->get($path));
        if ($mimeType === false) {
            throw new UnableToRetrieveMetadata("Unable to retrieve mime type for file at path: $path");
        }

        return new FileAttributes($path, null, null, null, $mimeType);
    }

    public function lastModified(string $path): FileAttributes
    {
        $path = '/' . ltrim($path, '/');
        $stat = $this->sftp->stat($path);
        if ($stat === false || !isset($stat['mtime'])) {
            throw new UnableToRetrieveMetadata("Unable to retrieve last modified time for file at path: $path");
        }

        return new FileAttributes($path, null, null, $stat['mtime']);
    }

    public function fileSize(string $path): FileAttributes
    {
        $path = '/' . ltrim($path, '/');
        $stat = $this->sftp->stat($path);
        if ($stat === false || !isset($stat['size'])) {
            throw new UnableToRetrieveMetadata("Unable to retrieve file size for file at path: $path");
        }

        return new FileAttributes($path, $stat['size']);
    }

    public function listContents(string $path, bool $deep): iterable
    {
        $path = '/' . ltrim($path, '/');
        $contents = $this->sftp->rawlist($path);

        if ($contents === false) {
            throw new UnableToRetrieveMetadata("Unable to list contents of directory at path: $path");
        }

        foreach ($contents as $item) {
            // Skip current directory (.) and parent directory (..)
            if ($item['filename'] === '.' || $item['filename'] === '..') {
                continue;
            }

            $itemPath = rtrim($path, '/') . '/' . $item['filename'];

            if ($item['type'] === 2) { // 2 indicates a directory
                yield DirectoryAttributes::fromArray(['type' => StorageAttributes::TYPE_DIRECTORY, 'path' => $itemPath]);

                // If deep listing is requested, recurse into subdirectories
                if ($deep) {
                    yield from $this->listContents($itemPath, true);
                }
            } else { // File
                yield FileAttributes::fromArray([
                    'type' => StorageAttributes::TYPE_FILE,
                    'path' => $itemPath,
                    'fileSize' => $item['size'] ?? null,
                    'lastModified' => $item['mtime'] ?? null,
                ]);
            }
        }
    }
}

