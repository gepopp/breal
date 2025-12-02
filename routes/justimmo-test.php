<?php

use App\Justimmo\Importer;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Justimmo Import Test Routes
|--------------------------------------------------------------------------
|
| These routes are for testing and debugging each step of the Justimmo
| import process. They should only be accessible in local/testing environments.
|
*/

// Step 1: Check for new zip files
Route::get('/justimmo-test/check-zip', function () {
    $importer = new Importer();
    $zipFiles = $importer->getAllZipFiles();

    if (empty($zipFiles)) {
        return response()->json([
            'status' => 'No zip files found',
            'files' => []
        ]);
    }

    // Sort by modification time (newest first)
    usort($zipFiles, function ($a, $b) {
        return $b['modified'] <=> $a['modified'];
    });

    return response()->json([
        'status' => 'Found ' . count($zipFiles) . ' zip file(s)',
        'latest' => $zipFiles[0],
        'all_files' => $zipFiles
    ]);
})->name('justimmo.test.check-zip');

// Step 2: Extract XML from zip
Route::get('/justimmo-test/extract-xml', function () {
    $importer = new Importer();
    $zipFiles = $importer->getAllZipFiles();

    if (empty($zipFiles)) {
        return response()->json([
            'status' => 'error',
            'message' => 'No zip files found'
        ], 404);
    }

    usort($zipFiles, function ($a, $b) {
        return $b['modified'] <=> $a['modified'];
    });

    $latestZipFile = $zipFiles[0];

    try {
        $extractedPath = $importer->extractZipFile($latestZipFile['path']);

        $xmlContent = file_get_contents(storage_path('app/public/' . $extractedPath));
        $fileSize = filesize(storage_path('app/public/' . $extractedPath));

        return response()->json([
            'status' => 'success',
            'zip_file' => $latestZipFile['filename'],
            'extracted_xml' => $extractedPath,
            'xml_size' => $fileSize,
            'xml_size_formatted' => round($fileSize / 1024, 2) . ' KB',
            'xml_preview' => substr($xmlContent, 0, 500) . '...'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
})->name('justimmo.test.extract-xml');

// Step 3: Extract individual property XMLs
Route::get('/justimmo-test/extract-properties', function () {
    $importer = new Importer();
    $zipFiles = $importer->getAllZipFiles();

    if (empty($zipFiles)) {
        return response()->json([
            'status' => 'error',
            'message' => 'No zip files found'
        ], 404);
    }

    usort($zipFiles, function ($a, $b) {
        return $b['modified'] <=> $a['modified'];
    });

    $latestZipFile = $zipFiles[0];

    try {
        $extractedPath = $importer->extractZipFile($latestZipFile['path']);
        $importer->extractBatchFiles($extractedPath);

        $batchFiles = $importer->getSortedFilesWithFileFacade('batches');

        return response()->json([
            'status' => 'success',
            'source_xml' => $extractedPath,
            'properties_extracted' => count($batchFiles),
            'sample_files' => array_slice($batchFiles, 0, 5)
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
})->name('justimmo.test.extract-properties');

// Step 4: Show a single property XML
Route::get('/justimmo-test/show-property/{index?}', function ($index = 0) {
    $importer = new Importer();
    $batchFiles = $importer->getSortedFilesWithFileFacade('batches');

    if (empty($batchFiles)) {
        return response()->json([
            'status' => 'error',
            'message' => 'No property files found. Run extract-properties first.'
        ], 404);
    }

    if (!isset($batchFiles[$index])) {
        return response()->json([
            'status' => 'error',
            'message' => 'Index out of range',
            'total_files' => count($batchFiles)
        ], 404);
    }

    $file = $batchFiles[$index];
    $xmlContent = file_get_contents(storage_path('app/public/' . $file['path']));

    $xml = simplexml_load_file(storage_path('app/public/' . $file['path']));
    $json = json_encode($xml);
    $array = json_decode($json, true);

    return response()->json([
        'status' => 'success',
        'file_index' => $index,
        'file_info' => $file,
        'openimmo_obid' => $array['verwaltung_techn']['openimmo_obid'] ?? null,
        'title' => $array['freitexte']['objekttitel'] ?? null,
        'xml_content' => $xmlContent,
        'parsed_data' => $array
    ]);
})->name('justimmo.test.show-property');

// Step 5: View all batch files
Route::get('/justimmo-test/list-batch-files', function () {
    $importer = new Importer();
    $batchFiles = $importer->getSortedFilesWithFileFacade('batches');

    return response()->json([
        'status' => 'success',
        'total_files' => count($batchFiles),
        'files' => $batchFiles
    ]);
})->name('justimmo.test.list-batch-files');

// Step 6: Cleanup test files
Route::get('/justimmo-test/cleanup', function () {
    $importer = new Importer();

    // Get all files
    $batchFiles = $importer->getSortedFilesWithFileFacade('batches');
    $importsFiles = $importer->getSortedFilesWithFileFacade('imports');

    $deletedCount = 0;

    // Delete batch files
    foreach ($batchFiles as $file) {
        $path = storage_path('app/public/' . $file['path']);
        if (file_exists($path)) {
            unlink($path);
            $deletedCount++;
        }
    }

    // Delete import XMLs (but not ZIPs)
    foreach ($importsFiles as $file) {
        if (str_contains($file['filename'], '.xml')) {
            $path = storage_path('app/public/' . $file['path']);
            if (file_exists($path)) {
                unlink($path);
                $deletedCount++;
            }
        }
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Cleanup completed',
        'files_deleted' => $deletedCount
    ]);
})->name('justimmo.test.cleanup');

// Step 7: Full process overview
Route::get('/justimmo-test/overview', function () {
    $importer = new Importer();

    $zipFiles = $importer->getAllZipFiles();
    $batchFiles = $importer->getSortedFilesWithFileFacade('batches');
    $importFiles = $importer->getSortedFilesWithFileFacade('imports');

    return response()->json([
        'status' => 'overview',
        'zip_files' => [
            'count' => count($zipFiles),
            'files' => $zipFiles
        ],
        'import_xmls' => [
            'count' => count(array_filter($importFiles, fn($f) => str_contains($f['filename'], '.xml'))),
            'files' => array_filter($importFiles, fn($f) => str_contains($f['filename'], '.xml'))
        ],
        'batch_files' => [
            'count' => count($batchFiles),
            'sample' => array_slice($batchFiles, 0, 3)
        ]
    ]);
})->name('justimmo.test.overview');
