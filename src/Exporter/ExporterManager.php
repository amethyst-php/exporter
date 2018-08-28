<?php

namespace Railken\LaraOre\Exporter;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Railken\LaraOre\Jobs\GenerateExporter;
use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\Result;
use Railken\Laravel\Manager\Tokens;
use Illuminate\Support\Collection;

class ExporterManager extends ModelManager
{
    /**
     * Class name entity.
     *
     * @var string
     */
    public $entity = Exporter::class;

    /**
     * List of all attributes.
     *
     * @var array
     */
    protected $attributes = [
        Attributes\Id\IdAttribute::class,
        Attributes\Name\NameAttribute::class,
        Attributes\CreatedAt\CreatedAtAttribute::class,
        Attributes\UpdatedAt\UpdatedAtAttribute::class,
        Attributes\DeletedAt\DeletedAtAttribute::class,
        Attributes\Input\InputAttribute::class,
        Attributes\Filename\FilenameAttribute::class,
        Attributes\Body\BodyAttribute::class,
        Attributes\RepositoryId\RepositoryIdAttribute::class,
    ];

    /**
     * List of all exceptions.
     *
     * @var array
     */
    protected $exceptions = [
        Tokens::NOT_AUTHORIZED => Exceptions\ExporterNotAuthorizedException::class,
    ];

    /**
     * Construct.
     *
     * @param AgentContract $agent
     */
    public function __construct(AgentContract $agent = null)
    {
        $this->entity = Config::get('ore.exporter.entity');
        $this->attributes = array_merge($this->attributes, array_values(Config::get('ore.exporter.attributes')));

        $classRepository = Config::get('ore.exporter.repository');
        $this->setRepository(new $classRepository($this));

        $classSerializer = Config::get('ore.exporter.serializer');
        $this->setSerializer(new $classSerializer($this));

        $classAuthorizer = Config::get('ore.exporter.authorizer');
        $this->setAuthorizer(new $classAuthorizer($this));

        $classValidator = Config::get('ore.exporter.validator');
        $this->setValidator(new $classValidator($this));

        parent::__construct($agent);
    }

    /**
     * Request a exporter.
     *
     * @param Exporter $exporter
     * @param array    $data
     *
     * @return \Railken\Laravel\Manager\Contracts\ResultContract
     */
    public function generate(Exporter $exporter, array $data = [])
    {
        $result = new Result();

        if (count((array) $exporter->input) !== 0) {
            $validator = Validator::make($data, Collection::make($exporter->input)->map(function($field) {
                return $field->validation;
            })->toArray());

            $errors = collect();

            foreach ($validator->errors()->getMessages() as $key => $error) {
                $errors[] = new Exceptions\ExporterInputException($key, $error[0], $data[$key]);
            }

            $result->addErrors($errors);
        }

        if (!$result->ok()) {
            return $result;
        }

        dispatch(new GenerateExporter($exporter, $data, $this->getAgent()));

        return $result;
    }
}
