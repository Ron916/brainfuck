<?php

namespace Ron916\Brainfuck;

use Ron916\Brainfuck\Contracts\BrainfuckDecoderInterface;
use Ron916\Brainfuck\Contracts\BrainfuckEncoderInterface;

/**
 * Class Brainfuck
 *
 * @package Ron916\Brainfuck
 */
class Brainfuck
{
    /**
     * @var BrainfuckEncoderInterface
     */
    private $encoder;

    /**
     * @var BrainfuckDecoderInterface
     */
    private $decoder;

    public function __construct(BrainfuckEncoderInterface $encoder, BrainfuckDecoderInterface $decoder)
    {
        $this->encoder = $encoder;
        $this->decoder = $decoder;
    }

    /**
     * @return BrainfuckEncoderInterface
     */
    public function getEncoder(): BrainfuckEncoderInterface
    {
        return $this->encoder;
    }

    /**
     * @return BrainfuckDecoderInterface
     */
    public function getDecoder(): BrainfuckDecoderInterface
    {
        return $this->decoder;
    }

    public function encode($text)
    {

    }

    public function decode($commands)
    {

    }
}