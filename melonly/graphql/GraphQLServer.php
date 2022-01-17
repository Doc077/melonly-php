<?php

namespace Melonly\GraphQL;

use GraphQL\GraphQL;
use Melonly\Database\DB;
use Melonly\Filesystem\File;
use Melonly\Http\Method as HttpMethod;
use Siler\Graphql as GraphQLSchema;

class GraphQLServer {
    protected readonly string $schema;

    protected readonly array $context;

    public function __construct() {
        if ($_SERVER['REQUEST_METHOD'] === HttpMethod::Post->value) {
            $this->schema = new GraphQLSchema\schema(
                File::content(__DIR__ . '/../database/graphql/schema.graphql'),
                include __DIR__ . '/../database/graphql/resolvers.php'
            );

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
