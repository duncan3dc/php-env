<?php

namespace duncan3dc\Env\Variables;

abstract class AbstractProvider implements ProviderInterface
{
    /**
     * @var array $vars Internal cache of environment variables.
     */
    private $vars;


    /**
     * Ensure all the environment variables are loaded.
     *
     * @return void
     */
    private function loadVars(): void
    {
        if ($this->vars !== null) {
            return;
        }

        try {
            $this->vars = $this->getVars();
        } catch (\Exception $e) {
            $this->vars = [];
        }
    }


    /**
     * Get all defined environment variables.
     *
     * @return array
     */
    abstract protected function getVars(): array;


    /**
     * @inheritdoc
     */
    public function has(string $var): bool
    {
        $this->loadVars();
        return array_key_exists($var, $this->vars);
    }


    /**
     * @inheritdoc
     */
    public function get(string $var)
    {
        $this->loadVars();
        return $this->vars[$var];
    }


    /**
     * @inheritdoc
     */
    public function set(string $var, $value): void
    {
        $this->loadVars();
        $this->vars[$var] = $value;
    }
}
