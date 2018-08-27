<?php

namespace src\Decorator;

use DateTime;
use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use src\Integration\DataProvider;
use src\Integration\ServiceProvider;

class DecoratorManager extends DataProvider
{
    private $cache;
    private $logger;

    /**
     * @param string $service
     * @param CacheItemPoolInterface $cache
     * @param LoggerInterface $logger
     */
    public function __construct(ServiceProvider $service, CacheItemPoolInterface $cache, LoggerInterface $logger)
    {
        parent::__construct($service);
        $this->logger = $logger;
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse(array $input)
    {
        try {
            $cacheKey = $this->getCacheKey($input);
            $cacheItem = $this->cache->getItem($cacheKey);
            if ($cacheItem->isHit()) {
                return $cacheItem->get();
            }

            $result = parent::get($input);

            $cacheItem
                ->set($result)
                ->expiresAt(
                    (new DateTime())->modify('+1 day')
                );

            return $result;
        } catch (Exception $e) {
            $this->logger->critical('Error');
        }

        return [];
    }

    private function getCacheKey(array $input)
    {
        return json_encode($input);
    }
}
