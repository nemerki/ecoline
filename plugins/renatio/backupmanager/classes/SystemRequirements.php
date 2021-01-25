<?php

namespace Renatio\BackupManager\Classes;

class SystemRequirements
{

    public function check()
    {
        $issues = [];

        $memoryLimit = $this->convertToBytes(ini_get('memory_limit'));
        $mb128 = 134217728;

        if ($memoryLimit !== -1 && $memoryLimit < $mb128) {
            $issues[] = e(trans(
                'renatio.backupmanager::lang.issue.memory_limit',
                ['limit' => ini_get('memory_limit')]
            ));
        }

        if (in_array('proc_open', explode(',', ini_get('disable_functions')))) {
            $issues[] = e(trans('renatio.backupmanager::lang.issue.proc_open'));
        }

        return $issues;
    }

    protected function convertToBytes($memoryLimit)
    {
        if ('-1' === $memoryLimit) {
            return -1;
        }

        $memoryLimit = strtolower($memoryLimit);
        $max = strtolower(ltrim($memoryLimit, '+'));
        if (0 === strpos($max, '0x')) {
            $max = \intval($max, 16);
        } elseif (0 === strpos($max, '0')) {
            $max = \intval($max, 8);
        } else {
            $max = (int) $max;
        }

        switch (substr($memoryLimit, -1)) {
            case 't':
                $max *= 1024;
            // no break
            case 'g':
                $max *= 1024;
            // no break
            case 'm':
                $max *= 1024;
            // no break
            case 'k':
                $max *= 1024;
        }

        return $max;
    }
}
