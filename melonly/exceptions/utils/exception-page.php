<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Exception<?= $exception->getCode() > 0 ? ' #' . $exception->getCode() : '' ?></title>

        <style>
            <?php include __DIR__ . '/styles.css' ?>
            <?php include __DIR__ . '/theme.css' ?>
        </style>

        <script src="https://cdn.jsdelivr.net/gh/google/code-prettify@master/loader/run_prettify.js"></script>
    </head>

    <body>
        <main class="container">
            <h1 class="header">Exception: <?= $exception->getMessage() ?? 'Error' ?></h1>

            <p class="info">
                <span class="info__value">File:</span> <strong class="bold"><?= $exceptionrrorFile ?? 'Unknown' ?></strong>
            </p>

            <p class="info">
                <span class="info__value">Line:</span> <strong class="bold"><?= $exception->getLine() ?? 'Unknown' ?></strong>
            </p>

            <p class="info">
                <span class="info__value">URL:</span> <strong class="bold"><?= $url ?? 'Unknown' ?></strong>
            </p>

            <p class="info">
                <span class="info__value">HTTP Method:</span> <strong class="bold"><?= $_SERVER['REQUEST_METHOD'] ?? 'Unknown' ?></strong>
            </p>

            <?php if (!empty($fileContent)): ?>
                <pre class="pre prettyprint"><code class="code">
                    <?php foreach ($fileContent as $index => $line): ?>
                        <div class="line<?= $index + 1 === $exception->getLine() ? ' line--error' : '' ?>"><?= htmlspecialchars($line) ?></div>
                    <?php endforeach; ?>
                </code></pre>
            <?php endif; ?>
        </main>
    </body>
</html>
