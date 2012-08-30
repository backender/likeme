<?php

namespace Likeme\SystemBundle\Gaufrette;

use Gaufrette\Filesystem;
use Gaufrette\StreamWrapper;

class StreamRegistry
{
    protected $streamDefinitions = array();

    /**
* Add a filesystem to be registered.
*
* @param string $domain
* @param \Gaufrette\Filesystem $filesystem
*
* @return StreamRegistry $this
*/
    public function addStreamDefinition($domain, Filesystem $filesystem)
    {
        $this->streamDefinitions[$domain] = $filesystem;

        return $this;
    }

    /**
* Register all added stream definitions using the Gaufrette\StreamWrapper.
*
* @param string $scheme The stream protocol scheme to use.
*
* @return StreamRegistry $this
*/
    public function register($scheme = 'gaufrette')
    {
     $map = StreamWrapper::getFilesystemMap();

        foreach ($this->streamDefinitions as $eachDomain => $eachFilesystem) {
            $map->set($eachDomain, $eachFilesystem);
        }

        StreamWrapper::register($scheme);

        return $this;
    }
}