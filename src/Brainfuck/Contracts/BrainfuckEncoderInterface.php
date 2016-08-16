<?php

namespace Ron916\Brainfuck\Contracts;

interface BrainfuckEncoderInterface
{
    public function encode($text): string;
}