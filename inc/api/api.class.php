<?php

// Resources
require_once 'resources/base.class.php';
require_once 'resources/cso/cso.class.php';
require_once 'resources/cso/enumeration.class.php';
require_once 'resources/cso/filter.class.php';
require_once 'resources/cso/parser.class.php';
require_once 'resources/openonderwijs/openonderwijs.class.php';
require_once 'resources/openonderwijs/enumeration.class.php';
require_once 'resources/openonderwijs/filter.class.php';
require_once 'resources/openonderwijs/parser.class.php';

class Api
{
    /**
     * @var array $resources Array with resource objects
     */
    private $resources = array();

    /**
     * Load all the resources we got
     */
    public function __construct()
    {
        $CSO = new Api_Resources_CSO();
        $CSO->authenticate('timojong', 'FG4d%!k3hU');
        $this->addResource('CSO', $CSO);

        $OpenOnderwijs = new Api_Resources_OpenOnderwijs();
        $this->addResource('OpenOnderwijs', $OpenOnderwijs);
    }

    /**
     * Gets all information from one job
     *
     * @param string $id The job id
     */
    public function getJob($id)
    {
        $parts = explode('-', $id);
        $resourceIdentifier = array_shift($parts);
        $id = implode('-', $parts);

        switch($resourceIdentifier)
        {
            case 'cso':
                $CSO = $this->getResource('CSO');
                return $CSO->getJob($id);
                break;

            case 'oo':
                $OpenOnderwijs = $this->getResource('OpenOnderwijs');
                return $OpenOnderwijs->getJob($id);
                break;
        }
    }

    /**
     * Gets the jobs from te resources
     *
     * @param mixed $filter Array with filters
     * @return array Jobs
     */
    public function getJobs($filter = false)
    {
        $jobs = array();

        $CSO = $this->getResource('CSO');
        foreach($CSO->getJobs($filter) as $job)
        {
            array_push($jobs, $job);
        }

        $OpenOnderwijs = $this->getResource('OpenOnderwijs');
        foreach($OpenOnderwijs->getJobs($filter) as $job)
        {
            array_push($jobs, $job);
        }

        return $jobs;
    }

    /**
     * Gets all the educations we got
     *
     * @return array Educations
     */
    public function getEducations()
    {
        $educations = array();

        $CSO = $this->getResource('CSO');
        $educations = array_merge($educations, $CSO->enumeration->getEducations());

        $OpenOnderwijs = $this->getResource('OpenOnderwijs');
        $educations = array_merge($educations, $OpenOnderwijs->enumeration->getEducations());

        return array_unique($educations);
    }

    public function getRegions()
    {
        $regions = array();

        $CSO = $this->getResource('CSO');
        $regions = array_merge($regions, $CSO->enumeration->getRegions());

        return $regions;
    }

    /**
     * Adds a resource to our resources
     *
     * @param string $name The resource name
     * @param object $resource The resource that we will add to the other resources we have got
     */
    private function addResource($name, $resource)
    {
        $this->resources[$name] = $resource;
    }

    /**
     * Gets a resource
     *
     * @param string $name The resource name
     * @return mixed The resource object
     */
    private function getResource($name)
    {
        if(isset($this->resources[$name]))
        {
            return $this->resources[$name];
        }
    }
}