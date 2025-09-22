<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Smalot\PdfParser\Parser;
use League\Csv\Reader;

class FileValidation implements ValidationRule
{
    public function __construct(protected int $min_pages = 1, protected int $max_pages, protected array $min_columns = []) {}
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $type_extension = $value->getClientOriginalExtension();
        if ($type_extension === 'pdf') {
            $parser = new Parser();
            $pdf = $parser->parseFile($value->getRealPath());
            $pages = $pdf->getPages();
            $num_pages = count($pages);
            if ($num_pages < $this->min_pages || $num_pages > $this->max_pages) {
                $fail("El archivo  PDF debe tener entre {$this->min_pages} y {$this->max_pages} páginas.");
            }
        } elseif ($type_extension === 'csv') {
            $csv = Reader::createFromPath($value->getRealPath(), 'r');
            $csv->setHeaderOffset(0);
            $headers = $csv->getHeader();
            foreach ($this->min_columns as $col) {
                if (!in_array($col, $headers)) {
                    $fail("El archivo CSV debe contener la columna '{$col}'.");
                }
            }
        }
    }
}
