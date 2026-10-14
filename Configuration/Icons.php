<?php

return [
    'extension-powermail-main' => [
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:powermail/Resources/Public/Icons/powermail.svg',
    ],
    // Backend module icon in the TYPO3 v14 module style (accent frame + currentColor glyph)
    'module-powermail' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:powermail/Resources/Public/Icons/module-powermail.svg',
    ],
];
