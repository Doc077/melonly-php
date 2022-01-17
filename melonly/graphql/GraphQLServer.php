<?php

namespace Melonly\GraphQL;

use GraphQL\GraphQL;
use Melonly\Database\DB;
use Melonly\Http\Method as HttpMethod;
use Siler\Graphql as GraphQLSchema;

class GraphQLServer {
    protected readonly string $schema;

    protected readonly array $context;

    public function __construct() {
        if ($_SERVER['REQUEST_METHOD'] === HttpMethod::Post->value) {
            $this->schema = require __DIR__ . '/../database/graphql/schema.graphql';

            $this->context = [
                'sql' => function ($query) {
                    return DB::query($query);
                }
            ];
        }
    }

    public function initizlize(): void {
        GraphQLSchema\init($this->schema, null, $this->context);
    }
}
