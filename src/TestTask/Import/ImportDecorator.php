<?php


namespace TestTask\Import;


class ImportDecorator implements ImportInterface
{
    private Reader $reader;
    private ImportInterface $import;

    public function __construct(Reader $reader, ImportInterface $import)
    {
        $this->reader = $reader;
        $this->import = $import;
    }

    public function process(): void
    {
        $this->import->process();
    }
}