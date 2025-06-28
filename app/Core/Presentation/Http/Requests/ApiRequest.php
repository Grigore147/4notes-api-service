<?php

declare(strict_types=1);

namespace App\Core\Presentation\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;
use App\Core\Application\QueryBus\Contracts\QueryContract;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Core\Presentation\Request\RequestQuery;
use App\Core\Application\CommandBus\Contracts\CommandContract;
use App\Core\Presentation\Http\Traits\ResponseStatus;

abstract class ApiRequest extends FormRequest // @pest-arch-ignore-line
{
    /**
     * Command class to be used
     *
     * @var string|null
     */
    public const COMMAND = null;

    /**
     * Query class to be used
     *
     * @var string|null
     */
    public const QUERY = null;

    /**
     * Request query
     *
     * @var RequestQuery|null
     */
    public ?RequestQuery $requestQuery = null;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Allow by default all requests, authorization is handled using Gates
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Format the errors from the validator.
     *
     * @param  Validator  $validator
     *
     * @return array
     */
    protected function formatErrors(Validator $validator) // @pest-arch-ignore-line
    {
        $validationErrors = $validator->errors()->toArray();

        $meta = [
            'requestId' => request()->header('X-Request-Id', Str::uuid()),
            'correlationId' => request()->header('X-Correlation-Id', Str::uuid())
        ];

        return [
            'status'      => ResponseStatus::ERROR,
            'code'        => Response::HTTP_UNPROCESSABLE_ENTITY,
            'message'     => 'Validation Error',
            'meta'        => $meta,
            'errors'      => $validationErrors
        ];
    }

    /**
     * Convert the request to a command
     *
     * @return CommandContract
     */
    public function toCommand(): CommandContract
    {
        return static::COMMAND::fromRequest($this);
    }

    /**
     * Convert the request to a query
     *
     * @return QueryContract
     */
    public function toQuery(): QueryContract
    {
        return static::QUERY::fromRequest($this);
    }
}
