<?php

namespace Ron916\Brainfuck\Contracts;

interface BrainfuckDecoderInterface
{
    public function decode($string): string;
}