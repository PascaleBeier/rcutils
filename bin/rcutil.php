#!/usr/bin/env php
<?php

require_once __DIR__.'/../vendor/autoload.php';

use Intervention\Image\ImageManagerStatic as Image;

use Symfony\Component\Console\ {
    Application,
    Input\InputArgument,
    Input\InputInterface,
    Output\OutputInterface,
    Style\SymfonyStyle
};

(new Application('rcutil', '1.0.0'))

/*
 |--------------------------------------------------------------------------
 | Image Command
 |--------------------------------------------------------------------------
 |
 | First, we register the image:resize command.
 |
 */
    ->register('image:resize')
        ->setDescription('Image Resizing')
        ->addArgument('input', InputArgument::REQUIRED, 'Path to the input image file')
        ->addArgument('output', InputArgument::REQUIRED, 'Path to the output image file')
        ->addArgument('width', InputArgument::REQUIRED, 'Output Image width')
        ->setCode(function(InputInterface $input, OutputInterface $output) {
            $io = new SymfonyStyle($input, $output);

            $io->section('image:resize');

            Image::configure(['driver' => 'gd']);
            Image::make($input->getArgument('input'))
                ->resize($input->getArgument('width'),null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save($input->getArgument('output'));

            $io->success('Resized '.$input->getArgument('input').' to '.$input->getArgument('width')
            .' and saved it to '.$input->getArgument('output'));
        })
    ->getApplication()
    ->run();