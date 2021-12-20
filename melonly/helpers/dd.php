<?php

function dd(...$variables): never {
    dump(...$variables);

    exit;
}
