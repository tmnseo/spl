<?php
/**
 * Copyright © Thomas Klein, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Zoho\Desk\Model\Operation;

use Zoho\Desk\Exception\CouldNotReadException;
use Zoho\Desk\Model\DataObjectInterface;

/**
 * @api
 */
interface ReadOperationInterface
{
    /**
     * @param int $entityId
     * @return DataObjectInterface
     * @throws CouldNotReadException
     */
    public function get(int $entityId): DataObjectInterface;
}
