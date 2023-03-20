<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Page $page
 */
$fields = [
    ['name' => 'page'],
    ['name' => 'section_name'],
    ['name' => 'section_heading'],
    ['name' => 'section_text']
];

$this->AdminDetail->info($fields, $page, $page->page . " - " . $page->section_name);
