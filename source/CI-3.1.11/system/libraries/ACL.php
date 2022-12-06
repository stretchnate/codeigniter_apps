<?php

class CI_ACL
{

    public $ci;

    public $config;

    /**
     * Constructor
     *
     * Load session library and configuration
     */
    public function __construct() {
        $this->ci = get_instance();
        $this->ci->load->library('session');

        include(APPPATH . 'config/acl' . '.php');
        if (isset($groups)) {
            $this->config['groups'] = $groups;
        }
        if (isset($roles)) {
            $this->config['roles'] = $roles;
        }
    }

    /**
     * Check whether or not a resource is available, optionally redirecting
     * @param int $resource
     * @param string $redirect_uri redirect here if the resource is not availabe
     * @return boolean
     */
    public function check($resource, $redirect_uri = NULL) {
        if (!in_array($resource, $this->getResources())) {
            if (!empty($redirect_uri)) {
                redirect($redirect_uri);
            }
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Get the time the resources were loaded
     * @return int
     */
    public function getTime() {
        $acl = $this->ci->session->acl;
        return $acl['time'] ?? 0;
    }

    /**
     * Get the resources array
     * @return array
     */
    public function getResources() {
        $acl = $this->ci->session->acl;
        return $acl['resources'] ?? [];
    }

    /**
     * Store user resources in the session 
     * @param array $user_resource_data
     * @return boolean
     */
    public function loadResources(array $user_resource_data) {
        $resources = $this->aggregateResources($user_resource_data);

        $acl = array('resources' => $resources, 'time' => time());
        $this->ci->session->acl = $acl;

        return TRUE;
    }

    /**
     * Aggregate user resources (permissions) from those given and configured
     * (assigned groups (of roles), roles (having resources), and resources))
     * @param array $user_resource_data
     * @return array
     */
    public function aggregateResources(array $user_resource_data) {
        $given_groups = isset($user_resource_data['groups']) ? (array) $user_resource_data['groups'] : array();
        $given_roles = isset($user_resource_data['roles']) ? (array) $user_resource_data['roles'] : array();
        //resources derived from given groups and roles will be merged with these
        $resources = isset($user_resource_data['resources']) ? (array) $user_resource_data['resources'] : array();

        $roles = isset($this->config['roles']) ? (array) $this->config['roles'] : [];
        $groups = isset($this->config['groups']) ? (array) $this->config['groups'] : [];

        //aggregate resources from given groups
        foreach ($given_groups as $given_group) {
            if (isset($groups[$given_group]['resources'])) {
                $resources = array_merge($resources, $groups[$given_group]['resources']);
            }
        }

        //aggregate resources from given roles
        foreach ($given_roles as $given_role) {
            if (isset($roles[$given_role])) {
                $resources = array_merge($resources, $roles[$given_role]);
            }
            foreach ($groups as $group) {
                if (isset($group['roles']) && isset($group['resources']) && in_array($given_role, $group['roles'])) {
                    $resources = array_merge($resources, $group['resources']);
                }
            }
        }

        $resources = array_unique($resources);
        sort($resources);

        return $resources;
    }

    /**
     * Aggregate group names from a given resource
     * @param int $resource
     * @return array
     */
    public function aggregateGroups($resource) {
        $groups = (array) $this->config['groups'];
        $agg_groups = array();

        foreach ($groups as $group_name => $group) {
            if (in_array($resource, $group['resources'])) {
                $agg_groups[] = $group_name;
            }
        }

        return $agg_groups;
    }

    /**
     * Aggregate role names from a given resource
     * @param int $resource
     * @return array
     */
    public function aggregateRoles($resource) {
        $roles = (array) $this->config['roles'];
        $groups = (array) $this->config['groups'];
        $agg_roles = array();

        foreach ($roles as $role_name => $role_resources) {
            //check group-role inhereted resources
            foreach ($groups as $group) {
                if (isset($group['roles']) && in_array($role_name, $group['roles'])) {
                    if (in_array($resource, $group['resources'])) {
                        $agg_roles[] = $role_name;
                    }
                }
            }
            //check role inhereted resources
            if (in_array($resource, $role_resources)) {
                $agg_roles[] = $role_name;
            }
        }

        return $agg_roles;
    }

}
