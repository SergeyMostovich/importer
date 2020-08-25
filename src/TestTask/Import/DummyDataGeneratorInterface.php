<?php


namespace TestTask\Import;


interface DummyDataGeneratorInterface
{
    public function setUsersCount(int $count): self;

    public function build(): void;

    public function setPath(string $path): self;
}