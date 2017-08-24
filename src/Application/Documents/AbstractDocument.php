<?php declare(strict_types = 1);

namespace WeCamp\TheDevelChase\Application\Documents;


abstract class AbstractDocument
{
    protected function sanitizeString(string $string): string
    {
        return strtolower(preg_replace(
            [
                '#[^a-z0-9\-_]#i',
                '#\-+#'
            ],
            '-',
            $string
        ));
    }
}
