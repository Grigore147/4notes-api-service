<?php

declare(strict_types=1);

namespace App\Core\Presentation\Request;

use Illuminate\Http\Request;

interface RequestQueryContract {
    public function get(string $key): mixed;
    public function has(string $key): bool;
    public function all(): array;
    public function getRequest(): ?Request;
    public function getRequestedFields($default = [], $key = 'fields'): array;
}

class RequestQuery implements RequestQueryContract
{
    protected ?array $requestedFields = null;

    public function __construct(
        protected ?Request $request = null,
        protected array $parameters = []
    ){}

    public static function fromRequest(Request $request): self
    {
        return new self(request: $request, parameters: $request->query->all());
    }

    public static function fromParameters(array $parameters): self
    {
        return new self(parameters: $parameters);
    }

    public function get(string $key): mixed
    {
        return $this->parameters[$key] ?? null;
    }

    public function has(string $key): bool
    {
        return isset($this->parameters[$key]);
    }

    public function all(): array
    {
        return $this->parameters;
    }

    public function getRequest(): ?Request
    {
        return $this->request;
    }

    public function getRequestedFields($defaultResource = 'default', $defaultFieldsets=['*']): array
    {
        if ($this->requestedFields !== null) {
            return $this->requestedFields;
        }

        if (!$this->has('fields')) {
            return $defaultFieldsets;
        }

        $fieldsets = $this->get('fields');

        if (is_string($fieldsets) || array_is_list($fieldsets)) {
            $fieldsets = [$defaultResource => $fieldsets];
        }

        $sets = [];

        foreach ($fieldsets as $type => $fields) {
            if (!$fields) { continue; }
            if (is_string($fields)) {
                $fields = explode(',', $fields);
            }

            $sets[$type] = array_unique(array_filter(array_map('trim', $fields)));
        }
        
        return $sets ? $this->requestedFields = $sets : $defaultFieldsets;
    }
}
