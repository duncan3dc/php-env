<?php

namespace duncan3dc\Env\Variables;

abstract class AbstractProvider implements ProviderInterface
{
    /**
     * @var array $vars Internal cache of environment variables.
     */
    private $vars;


    /**
     * Get all defined environment variables.
     *
     * @return null
     */
    private function loadVars()
    {
        if ($this->vars !== null) {
            return;
        }

        try {
            $this->vars = $this->getVars();
        } catch (\Exception $e) {
            $this->vars = [];
        }

        return $this->vars;
    }


    /**
     * Get all defined environment variables.
     *
     * @return array
     */
    abstract protected function getVars();


    /**
     * Check if a specific environment variable exists.
     *
     * @param string $var The name of the variable to check
     *
     * @return bool
     */
    public function has($var)
    {
        $this->loadVars();
        return array_key_exists($var, $this->vars);
    }


    /**
     * Get a specific environment variable.
     *
     * @param string $var The name of the variable to retrieve
     *
     * @return mixed
     */
    public function get($var)
    {
        $this->loadVars();
        return $this->vars[$var];
    }


    /**
     * Override an environment variable.
     *
     * @param string $var The name of the variable to set
     * @param string|int|boolean $value The value of the environment variable
     *
     * @return $this
     */
    public function set($var, $value)
    {
        $this->loadVars();
        $this->vars[$var] = $value;
        return $this;
    }
}
