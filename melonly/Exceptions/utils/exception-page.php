<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Exception<?= $exception->getCode() > 0 ? ' #' . $exception->getCode() : '' ?></title>

        <style>
            <?php include __DIR__ . '/styles.css' ?>
            <?php include __DIR__ . '/syntax.css' ?>
        </style>

        <script src="https://cdn.jsdelivr.net/gh/google/code-prettify@master/loader/run_prettify.js"></script>
    </head>

    <body>
        <main class="container">
            <div class="topbar">
                <div class="topbar__type"><?= $fullExceptionType ?></div>

                <div class="topbar__version">Melonly <?= MELONLY_VERSION ?></div>
            </div>

            <h1 class="header"><?= $exceptionType ?>: <?= $exception->getMessage() . (substr($exception->getMessage(), -1) !== '.' ? '.' : '') ?? 'Exception' ?></h1>

            <p class="info">
                <span class="info__value">File:</span> <strong class="bold"><?= $exceptionFile ?? 'Unknown' ?></strong>
            </p>

            <p class="info">
                <span class="info__value">Line:</span> <strong class="bold"><?= $exception->getLine() ?? 'Unknown' ?></strong>
            </p>

            <p class="info">
                <span class="info__value">URI:</span> <strong class="bold"><?= $url ?? 'Unknown' ?></strong>
            </p>

            <p class="info">
                <span class="info__value">HTTP Method:</span> <strong class="bold"><?= $_SERVER['REQUEST_METHOD'] ?? 'Unknown' ?></strong>
            </p>

            <?php if ($exception->getCode() > 0): ?>
                <p class="info">
                    <span class="info__value">Exception code:</span> <strong class="bold">#<?= $exception->getCode() ?></strong>
                </p>
            <?php endif; ?>

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