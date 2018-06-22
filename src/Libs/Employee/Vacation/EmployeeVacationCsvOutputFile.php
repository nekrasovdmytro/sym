<?php

namespace App\Libs\Employee\Vacation;


use App\Entity\VacationCalculableEmployeeInterface;
use App\Libs\IO\OutputFileInterface;

class EmployeeVacationCsvOutputFile implements OutputFileInterface
{
    private const OUTPUT_DIR = __DIR__ . '/../../../../public/';

    protected $list;
    protected $filePrefix;

    /**
     * List elements must be implemented with VacationCalculableEmployeeInterface
     *
     * @param \Traversable $list
     * @return EmployeeVacationCsvOutputFile
     */
    public function setData(\Traversable $list)
    {
        $this->list = $list;

        return $this;
    }

    public function setFilePrefix(string $name)
    {
        $this->filePrefix = $name;

        return $this;
    }

    /**
     * Returns filename of output
     *
     * @return string
     */
    public function output(): string
    {
        if (!is_dir(self::OUTPUT_DIR)) {
            throw new \RuntimeException(self::OUTPUT_DIR . ' is not a dir');
        }

        if (!is_writable(self::OUTPUT_DIR)) {
            throw new \RuntimeException(self::OUTPUT_DIR . ' is not writable');
        }

        $filePath = self::OUTPUT_DIR . $this->getFilePrefix() . '.csv';
        $fp = fopen($filePath, 'w');

        foreach ($this->list as $element) { /** @var VacationCalculableEmployeeInterface $element */
            $fields = [
                $element->getFirstName(),
                $element->getLastName(),
                $element->getEmployeeVacationAmountPerYear()
            ];
            fputcsv($fp, $fields);
        }

        fclose($fp);

        return $filePath;
    }

    public function getFilePrefix(): string
    {
        return $this->filePrefix;
    }
}