<?php

declare(strict_types=1);

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carbon\TranslationLoader;

use Symfony\Component\Translation\Loader\PhpFileLoader as SymfonyPhpFileLoader;

final class PhpFileLoader extends SymfonyPhpFileLoader
{
    public function loadResource(string $resource): array
    {
        return parent::loadResource($resource);
    }
}
