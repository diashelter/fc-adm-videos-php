<?php

declare(strict_types=1);

use App\Services\Storage\FileStorage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->fakeFile = UploadedFile::fake()->create('video.mp4', 1, 'video/mp4');
});

it('should store file upload', function () {
    $file = [
        'tmp_name' => $this->fakeFile->getPathname(),
        'name' => $this->fakeFile->getClientOriginalName(),
        'type' => $this->fakeFile->getMimeType(),
        'error' => $this->fakeFile->getError(),
    ];
    $fileStorage = (new FileStorage())->store('videos', $file);

    Storage::assertExists($fileStorage);

    Storage::delete($fileStorage);
});

it('should delete file in storage', function () {

    $path = $this->fakeFile->store('videos');

    (new FileStorage())->delete($path);

    Storage::assertMissing($path);
});
