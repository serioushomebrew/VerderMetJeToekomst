<?php

// Resources
require_once 'resources/base.class.php';
require_once 'resources/enumeration.class.php';
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

        return $this->output($jobs);
    }

    /**
     * Returns all possible branches
     *
     * @param bool $json [optional] Output json
     * @return string Branches
     */
    public function getBranches($json = true)
    {
        $branches = array();

        $CSO = $this->getResource('CSO');
        $branches = array_merge($branches, $CSO->getBranches());

        $OpenOnderwijs = $this->getResource('OpenOnderwijs');
        $branches = array_merge($branches, $OpenOnderwijs->getBranches());

        // prevent duplicated
        $branches = array_unique($branches);
        $branches = array_values($branches);

        if($json === true)
        {
            return $this->output($branches);
        }
        else
        {
            return $branches;
        }
    }

    /**
     * Gets all the educations we got
     *
     * @param bool $json [optional] Output json
     * @return string Educations
     */
    public function getEducations($json = true)
    {
        $educations = array();

        $CSO = $this->getResource('CSO');
        $educations = array_merge($educations, $CSO->getEducations());

        $OpenOnderwijs = $this->getResource('OpenOnderwijs');
        $educations = array_merge($educations, $OpenOnderwijs->getEducations());

        // prevent duplicated
        $educations = array_unique($educations);
        $educations = array_values($educations);


        if($json === true)
        {
            return $this->output($educations);
        }
        else
        {
            return $educations;
        }
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

    /**
     * Json encodes the data for output
     *
     * @param array $data
     * @return string JSON
     */
    private function output($data)
    {
        return json_encode($data);
    }
}