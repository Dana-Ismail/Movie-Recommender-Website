<?php


$analysisDoneFile = __DIR__ . '/storage/framework/analysis_done';

if (!file_exists($analysisDoneFile)) {
    exec('python3 ' . __DIR__ . '/resources/notebooks/analysis.py');
    file_put_contents($analysisDoneFile, 'analysis_done');
}
